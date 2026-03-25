<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateOldData extends Command
{
    protected $signature = 'migrate:old-data {file? : The path to the SQL file} {--skip-import : Skip filtering and importing SQL files}';

    protected $description = 'Migrate data from old database SQL dump';

    public function handle()
    {
        ini_set('memory_limit', '-1');
        $file = $this->argument('file') ?? 'database/sql_core.sql';

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return;
        }

        // Define separate filtered files for better granularity
        $files = [
            'product' => 'database/filtered_products.sql',
            'branch' => 'database/filtered_branches.sql',
            'news' => 'database/filtered_news.sql',
            'page' => 'database/filtered_pages.sql',
            'meta' => 'database/filtered_meta.sql',
        ];

        // 1. FILTER SQL FILE
        if (!$this->option('skip-import')) {
            $needsFiltering = false;
            foreach ($files as $f) {
                if (!file_exists($f)) {
                    $needsFiltering = true;
                    break;
                }
            }

            if ($needsFiltering || $this->confirm("Filtered files exist. Re-create?", true)) {
                $this->info("Filtering SQL file: $file into chunks...");

                $handleIn = fopen($file, "r");
                $handles = [];
                foreach ($files as $key => $path) {
                    $handles[$key] = fopen($path, "w");
                }

                if ($handleIn && !in_array(false, $handles)) {
                    $sql = '';
                    $count = 0;
                    while (($line = fgets($handleIn)) !== false) {
                        if (trim($line) == '' || str_starts_with($line, '--') || str_starts_with($line, '/*')) {
                            continue;
                        }
                        $sql .= $line;
                        if (str_ends_with(trim($line), ';')) {
                            // Check if this statement is for our tables
                            $prefix = substr($sql, 0, 1000);

                            if (preg_match('/INSERT INTO `(product|product_cms|product_serial)`/i', $prefix)) {
                                fwrite($handles['product'], $sql . "\n");
                            } elseif (preg_match('/INSERT INTO `(branch|branch_cms)`/i', $prefix)) {
                                fwrite($handles['branch'], $sql . "\n");
                            } elseif (preg_match('/INSERT INTO `(news|news_category)`/i', $prefix)) {
                                fwrite($handles['news'], $sql . "\n");
                            } elseif (preg_match('/INSERT INTO `(about|page)`/i', $prefix)) {
                                fwrite($handles['page'], $sql . "\n");
                            } elseif (preg_match('/INSERT INTO `(product_attribute|product_group|product_group_cms|product_brand|product_brand_cms|news_category|branch_cms_group)`/i', $prefix)) {
                                fwrite($handles['meta'], $sql . "\n");
                            }

                            $count++;
                            if ($count % 500 == 0) $this->info("Processed $count queries...");
                            $sql = '';
                        }
                    }
                    fclose($handleIn);
                    foreach ($handles as $h) fclose($h);
                    $this->info("Filtering complete.");
                } else {
                    $this->error("Could not open files for filtering.");
                    return;
                }
            }

            // 2. IMPORT FILTERED FILES
            $this->info("Importing filtered chunk files...");

            foreach ($files as $key => $path) {
                if (!file_exists($path) || filesize($path) < 100) continue; // Skip empty files

                $this->info("Importing $key data from $path...");

                // Try explicit mysql command first
                $dbConfig = config('database.connections.mysql');
                $cmd = "mysql -h {$dbConfig['host']} -u {$dbConfig['username']} " . ($dbConfig['password'] ? "-p{$dbConfig['password']} " : "") . "--max_allowed_packet=512M {$dbConfig['database']} < \"$path\"";

                exec($cmd, $output, $returnVar);

                if ($returnVar !== 0) {
                    $this->warn("System import failed for $key. Trying PHP import...");
                    try {
                        DB::unprepared(file_get_contents($path));
                        $this->info("PHP Import successful for $key!");
                    } catch (\Exception $e) {
                        $this->error("PHP Import failed for $key: " . $e->getMessage());
                        $this->warn("Please import '$path' manually.");
                    }
                } else {
                    $this->info("Import successful for $key!");
                }
            }
        }

        // 3. MIGRATE DATA
        $this->info("Starting Data Migration to New Tables...");

        $tables = DB::select('SHOW TABLES');
        $this->info("Tables in DB: " . implode(', ', array_map(fn($t) => array_values((array)$t)[0], $tables)));

        DB::beginTransaction();

        try {
            $this->migrateCategories();
            $this->migrateBrands();
            $this->migratePages();
            $this->migrateBranches();
            $this->migrateProducts();
            $this->migrateArticles();
            $this->migrateAttributes();

            DB::commit();
            $this->info("Migration completed successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            $this->line($e->getTraceAsString());
        }
    }

    private function migrateAttributes()
    {
        $this->info("Migrating Attributes...");

        if (!Schema::hasTable('product_attribute')) {
            $this->warn("Table 'product_attribute' not found. Skipping.");
            return;
        }

        // 1. Migrate Attribute Definitions
        $oldAttrs = DB::table('product_attribute')->get();
        $attrMap = []; // old_id => new_attribute_model

        foreach ($oldAttrs as $oldAttr) {
            $newAttr = \App\Models\Attribute::updateOrCreate(
                ['slug' => $oldAttr->code],
                [
                    'name' => $oldAttr->name,
                    'type' => 'text', // default
                    'is_visible' => $oldAttr->status == 1,
                    'is_filterable' => true,
                    'order' => $oldAttr->sort ?? 0,
                ]
            );
            $attrMap[$oldAttr->id] = $newAttr;
        }
        $this->info("Migrated " . count($attrMap) . " attribute definitions.");

        // 2. Migrate Product Attribute Values
        if (!Schema::hasTable('product_cms')) return;

        $products = DB::table('product_cms')->whereNotNull('attributes')->where('attributes', '!=', '')->get();
        $count = 0;

        foreach ($products as $oldProd) {
            $attrs = json_decode($oldProd->attributes, true);
            if (!is_array($attrs)) continue;

            $newProd = \App\Models\Product::where('slug', $oldProd->link)->first();
            if (!$newProd) {
                // Try fallback slug if link was null
                $newProd = \App\Models\Product::where('slug', Str::slug($oldProd->title))->first();
            }

            if (!$newProd) continue;

            foreach ($attrs as $oldAttrId => $value) {
                if (empty($value) || !isset($attrMap[$oldAttrId])) continue;

                $attribute = $attrMap[$oldAttrId];

                // Find or create the value
                $attrValue = \App\Models\AttributeValue::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ],
                    [
                        'slug' => Str::slug($value) ?: Str::random(8),
                    ]
                );

                // Link to product
                $newProd->attributeValues()->syncWithoutDetaching([$attrValue->id => ['price_adjustment' => 0]]);
                $count++;
            }
        }
        $this->info("Migrated $count product attribute assignments.");
    }

    private function migrateCategories()
    {
        $this->info("Migrating Categories...");

        // Product Categories
        $pgTable = Schema::hasTable('product_group_cms') ? 'product_group_cms' : (Schema::hasTable('product_group') ? 'product_group' : null);
        if ($pgTable) {
            $groups = DB::table($pgTable)->get();
            foreach ($groups as $g) {
                \App\Models\Category::updateOrCreate(
                    ['slug' => $g->link ?? Str::slug($g->name ?? $g->title)],
                    [
                        'name' => $g->name ?? $g->title,
                        'description' => $g->description ?? $g->note ?? null,
                        'image' => $this->normalizeImagePath($g->picture ?? null),
                        'type' => 'product',
                        'is_active' => ($g->status ?? 1) == 1,
                        'order' => $g->sort ?? 0,
                    ]
                );
            }
        }

        // News Categories
        if (Schema::hasTable('news_category')) {
            $ncats = DB::table('news_category')->get();
            foreach ($ncats as $c) {
                \App\Models\Category::updateOrCreate(
                    ['slug' => $c->link ?? Str::slug($c->title)],
                    [
                        'name' => $c->title,
                        'type' => 'article',
                        'is_active' => ($c->status ?? 1) == 1,
                        'order' => $c->sort ?? 0,
                    ]
                );
            }
        }
    }

    private function migrateBrands()
    {
        $this->info("Migrating Brands...");
        $bTable = Schema::hasTable('product_brand_cms') ? 'product_brand_cms' : (Schema::hasTable('product_brand') ? 'product_brand' : null);
        if ($bTable) {
            $brands = DB::table($bTable)->get();
            foreach ($brands as $b) {
                \App\Models\Brand::updateOrCreate(
                    ['slug' => $b->link ?? Str::slug($b->title ?? $b->name)],
                    [
                        'name' => $b->title ?? $b->name,
                        'description' => $b->description ?? null,
                        'logo' => $this->normalizeImagePath($b->picture ?? null),
                        'is_active' => ($b->status ?? 1) == 1,
                        'order' => $b->sort ?? 0,
                    ]
                );
            }
        }
    }

    private function normalizeImagePath($path)
    {
        if (empty($path)) return null;

        // Strip domain names
        $domains = [
            'https://365group.com.vn/',
            'https://gtrvietnam.com/',
            'https://auto365.vn/',
            'http://365group.com.vn/',
            'http://gtrvietnam.com/',
            'http://auto365.vn/',
        ];

        $path = str_replace($domains, '', $path);

        // Strip leading slash
        $path = ltrim($path, '/');

        // Ensure it starts with uploads/ (older paths might use upload/)
        if (strpos($path, 'upload/') === 0 && strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . ltrim($path, 'upload/');
        }

        // Final check: if it doesn't start with uploads/, it might be a relative path already
        // But the old pattern for images always had /uploads/ or /upload/
        return $path;
    }

    private function migratePages()
    {
        $this->info("Migrating About -> Pages...");
        if (!Schema::hasTable('about')) return;

        $abouts = DB::table('about')->get();
        foreach ($abouts as $item) {
            \App\Models\Page::updateOrCreate(
                ['slug' => $item->link],
                [
                    'title' => $item->title,
                    'content' => $item->description,
                    'featured_image' => $this->normalizeImagePath($item->picture),
                    'is_active' => $item->status == 1,
                    'meta_description' => $item->meta_desc ?? null,
                    'meta_keywords' => $item->meta_key ?? null,
                ]
            );
        }
        $this->info("Migrated " . $abouts->count() . " pages.");
    }

    private function migrateBranches()
    {
        // Try multiple potential table names
        $tables = ['branch_cms', 'branch', 'brand_cms'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                $this->info("Migrating $tableName -> Branches...");
                $branches = DB::table($tableName)->get();
                foreach ($branches as $item) {
                    \App\Models\Branch::updateOrCreate(
                        ['slug' => $item->link ?? Str::slug($item->title)],
                        [
                            'name' => $item->title,
                            'address' => $item->address ?? null,
                            'content' => $item->description ?? null,
                            'image' => $this->normalizeImagePath($item->picture),
                            'is_active' => isset($item->status) ? $item->status == 1 : true,
                            'order' => $item->sort ?? 0,
                        ]
                    );
                }
                $this->info("Migrated " . $branches->count() . " branches from $tableName.");
                return;
            }
        }
        $this->warn("No Branch table found (checked: " . implode(', ', $tables) . "). Skipping.");
    }

    private function migrateProducts()
    {
        // Try multiple potential table names
        $tables = ['product_cms', 'product'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                $this->info("Migrating $tableName -> Products...");
                $products = DB::table($tableName)->get();
                foreach ($products as $item) {
                    $newProd = \App\Models\Product::updateOrCreate(
                        ['slug' => $item->link ?? Str::slug($item->title)],
                        [
                            'name' => $item->title ?? $item->name ?? 'Untitled Product',
                            'sku' => $item->code ?? null,
                            'short_description' => $item->short ?? null,
                            'content' => $item->content ?? $item->description ?? '',
                            'featured_image' => $this->normalizeImagePath($item->picture),
                            'price' => $item->price ?? 0,
                            'sale_price' => $item->price_promotion ?? 0,
                            'is_active' => isset($item->status) ? $item->status == 1 : true,
                            'is_featured' => isset($item->focus) ? $item->focus == 1 : false,
                            'view_count' => $item->view ?? 0,
                            'meta_description' => $item->meta_desc ?? null,
                            'meta_keywords' => $item->meta_key ?? null,
                        ]
                    );

                    // Map Category
                    // Map Category
                    if ($item->group_id) {
                        $targetPgTable = $pgTable ??
                            (Schema::hasTable('product_group_cms') ? 'product_group_cms' : (Schema::hasTable('product_group') ? 'product_group' : null));

                        if ($targetPgTable) {
                            $query = DB::table($targetPgTable)->where('id', $item->group_id);

                            if ($targetPgTable === 'product_group') {
                                $query->orWhere('tmp_id', $item->group_id);
                            }

                            $group = $query->first();

                            if ($group) {
                                $cat = \App\Models\Category::where('slug', $group->link ?? Str::slug($group->name ?? $group->title))->first();
                                if ($cat) {
                                    $newProd->update(['category_id' => $cat->id]);
                                }
                            }
                        }
                    }

                    // Map Brand
                    if ($item->brand_id) {
                        $targetBTable = $bTable ??
                            (Schema::hasTable('product_brand_cms') ? 'product_brand_cms' : (Schema::hasTable('product_brand') ? 'product_brand' : null));

                        if ($targetBTable) {
                            $brand = DB::table($targetBTable)
                                ->where('id', $item->brand_id)
                                ->first();
                            if ($brand) {
                                $newBrand = \App\Models\Brand::where('slug', $brand->link ?? Str::slug($brand->title ?? $brand->name))->first();
                                if ($newBrand) {
                                    $newProd->update(['brand_id' => $newBrand->id]);
                                }
                            }
                        }
                    }
                }
                $this->info("Migrated " . $products->count() . " products from $tableName.");
                return;
            }
        }
        $this->warn("No Product table found (checked: " . implode(', ', $tables) . "). Skipping.");
    }

    private function migrateArticles()
    {
        $this->info("Migrating News -> Articles...");
        // 'news' table
        if (Schema::hasTable('news')) {
            $news = DB::table('news')->get();
            foreach ($news as $item) {
                $article = \App\Models\Article::updateOrCreate(
                    ['slug' => $item->link ?? Str::slug($item->title)],
                    [
                        'title' => $item->title,
                        'excerpt' => $item->short ?? null,
                        'content' => $item->description ?? '',
                        'featured_image' => $this->normalizeImagePath($item->picture),
                        'status' => (isset($item->status) && $item->status == 1) ? 'published' : 'draft',
                        'published_at' => $item->created_at ?? now(),
                        'view_count' => $item->view ?? 0,
                        'meta_description' => $item->meta_desc ?? null,
                        'meta_keywords' => $item->meta_key ?? null,
                    ]
                );

                if ($item->category_id) {
                    $catTable = Schema::hasTable('news_category') ? 'news_category' : null;
                    if ($catTable) {
                        $oldCat = DB::table($catTable)->where('id', $item->category_id)->first();
                        if ($oldCat) {
                            $cat = \App\Models\Category::where('slug', $oldCat->link ?? Str::slug($oldCat->title))->where('type', 'article')->first();
                            if ($cat) {
                                $article->update(['category_id' => $cat->id]);
                            }
                        }
                    }
                }
            }
            $this->info("Migrated " . $news->count() . " articles.");
        } else {
            $this->warn("Table 'news' not found. Skipping.");
        }
    }
}
