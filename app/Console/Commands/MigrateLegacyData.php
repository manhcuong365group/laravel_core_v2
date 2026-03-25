<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:legacy {--tenant= : The domain of the tenant to migrate to} {--import : Import the SQL file first}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from legacy SQL database to the new Laravel system';

    protected array $idMapping = [
        'category' => [],
        'brand' => [],
        'user' => [],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantDomain = $this->option('tenant') ?? 'localhost';
        $tenant = Tenant::where('domain', $tenantDomain)->first();

        if (!$tenant) {
            $this->error("Tenant with domain [{$tenantDomain}] not found.");
            return 1;
        }

        $this->info("Starting migration for tenant: {$tenant->name} ({$tenant->domain})");

        if ($this->option('import')) {
            $this->importSqlFile();
        }

        // Verify if basic tables exist
        if (!Schema::hasTable('table_news') && !Schema::hasTable('table_product')) {
            $this->error("Legacy tables not found. Please import the SQL file first using --import.");
            return 1;
        }

        DB::beginTransaction();
        try {
            $this->migrateCategories();
            $this->migrateBrands();
            $this->migrateProducts();
            $this->migrateArticles();
            $this->migrateMembers();

            DB::commit();
            $this->info("Migration completed successfully for {$tenant->domain}.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    protected function importSqlFile()
    {
        $path = database_path('db_core.sql');
        if (!file_exists($path)) {
            $this->error("SQL file not found at {$path}");
            exit(1);
        }

        $this->info("Importing legacy SQL file... this may take a while.");

        $dbConfig = config('database.connections.mysql');

        // Pass empty password check
        $passArg = empty($dbConfig['password']) ? '' : '-p' . $dbConfig['password'];

        $command = sprintf(
            'mysql -h %s -P %s -u %s %s %s < %s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['username'],
            $passArg,
            $dbConfig['database'],
            escapeshellarg($path)
        );

        // For security reasons, don't use shell_exec with passwords if possible, but for dev it's okay.
        $result = shell_exec($command);
        $this->info("Import finished.");
    }

    protected function migrateCategories()
    {
        $this->info("Migrating categories...");

        // Level 1: Product list
        if (Schema::hasTable('table_product_list')) {
            $lists = DB::table('table_product_list')->get();
            foreach ($lists as $list) {
                $cat = Category::updateOrCreate(
                    ['slug' => $list->slugvi ?: Str::slug($list->namevi)],
                    [
                        'name' => $list->namevi,
                        'type' => 'product',
                        'order' => $list->numb ?? 0,
                        'is_active' => $list->status === 'hienthi',
                    ]
                );
                $this->idMapping['category']['list_' . $list->id] = $cat->id;
            }
        }

        // Level 2: Product cat (parent: id_list)
        if (Schema::hasTable('table_product_cat')) {
            $cats = DB::table('table_product_cat')->get();
            foreach ($cats as $c) {
                $parentId = $this->idMapping['category']['list_' . $c->id_list] ?? null;
                $cat = Category::updateOrCreate(
                    ['slug' => $c->slugvi ?: Str::slug($c->namevi)],
                    [
                        'name' => $c->namevi,
                        'type' => 'product',
                        'parent_id' => $parentId,
                        'order' => $c->numb ?? 0,
                        'is_active' => $c->status === 'hienthi',
                    ]
                );
                $this->idMapping['category']['cat_' . $c->id] = $cat->id;
            }
        }

        // News categories
        if (Schema::hasTable('table_news_list')) {
            $newsLists = DB::table('table_news_list')->get();
            foreach ($newsLists as $list) {
                $cat = Category::updateOrCreate(
                    ['slug' => $list->slugvi ?: Str::slug($list->namevi)],
                    [
                        'name' => $list->namevi,
                        'type' => 'news',
                        'order' => $list->numb ?? 0,
                        'is_active' => $list->status === 'hienthi',
                    ]
                );
                $this->idMapping['category']['news_list_' . $list->id] = $cat->id;
            }
        }
    }

    protected function migrateBrands()
    {
        $this->info("Migrating brands...");

        if (Schema::hasTable('table_product_brand')) {
            $brands = DB::table('table_product_brand')->get();
            foreach ($brands as $b) {
                $brand = Brand::updateOrCreate(
                    ['slug' => $b->slugvi ?: Str::slug($b->namevi)],
                    [
                        'name' => $b->namevi,
                        'description' => $b->descvi,
                        'order' => $b->numb ?? 0,
                        'is_active' => $b->status === 'hienthi',
                    ]
                );
                $this->idMapping['brand'][$b->id] = $brand->id;
            }
        }
    }

    protected function migrateProducts()
    {
        $this->info("Migrating products...");

        if (Schema::hasTable('table_product')) {
            DB::table('table_product')->orderBy('id')->chunk(100, function ($products) {
                foreach ($products as $p) {
                    // Determine category (list or cat)
                    $categoryId = null;
                    if ($p->id_item > 0) {
                        // In some schemas item/sub/cat hierarchy is complex
                    }
                    if ($p->id_cat > 0) {
                        $categoryId = $this->idMapping['category']['cat_' . $p->id_cat] ?? null;
                    }
                    if (!$categoryId && $p->id_list > 0) {
                        $categoryId = $this->idMapping['category']['list_' . $p->id_list] ?? null;
                    }

                    $brandId = $this->idMapping['brand'][$p->id_brand] ?? null;

                    Product::updateOrCreate(
                        ['slug' => $p->slugvi ?: Str::slug($p->namevi ?: 'product-' . $p->id)],
                        [
                            'name' => $p->namevi ?: 'Untitled Product',
                            'sku' => $p->code,
                            'short_description' => $p->descvi,
                            'content' => $p->contentvi,
                            'price' => $p->regular_price ?: 0,
                            'sale_price' => $p->sale_price ?: 0,
                            'category_id' => $categoryId,
                            'brand_id' => $brandId,
                            'is_active' => Str::contains($p->status, 'hienthi'),
                            'is_featured' => Str::contains($p->status, 'noibat'),
                            'order' => $p->numb ?? 0,
                            'view_count' => $p->view ?? 0,
                            'created_at' => date('Y-m-d H:i:s', $p->date_created ?: time()),
                            'updated_at' => date('Y-m-d H:i:s', $p->date_updated ?: time()),
                        ]
                    );
                }
            });
        }
    }

    protected function migrateArticles()
    {
        $this->info("Migrating articles...");

        if (Schema::hasTable('table_news')) {
            DB::table('table_news')->orderBy('id')->chunk(100, function ($news) {
                foreach ($news as $item) {
                    // Determine category
                    $categoryId = null;
                    if ($item->id_list > 0) {
                        $categoryId = $this->idMapping['category']['news_list_' . $item->id_list] ?? null;
                    }

                    $type = 'post';
                    if ($item->type === 'tin-tuc') $type = 'news';
                    if ($item->type === 'dich-vu') $type = 'service';

                    Article::updateOrCreate(
                        ['slug' => $item->slugvi ?: Str::slug($item->namevi ?: 'post-' . $item->id)],
                        [
                            'title' => $item->namevi ?: 'Untitled',
                            'content' => $item->contentvi,
                            'excerpt' => $item->descvi,
                            'category_id' => $categoryId,
                            'type' => $type,
                            'status' => $item->status === 'hienthi' ? 'published' : 'draft',
                            'view_count' => $item->view,
                            'published_at' => date('Y-m-d H:i:s', $item->date_created ?: time()),
                            'created_at' => date('Y-m-d H:i:s', $item->date_created ?: time()),
                            'updated_at' => date('Y-m-d H:i:s', $item->date_updated ?: time()),
                        ]
                    );
                }
            });
        }

        // Static pages
        if (Schema::hasTable('table_static')) {
            $statics = DB::table('table_static')->get();
            foreach ($statics as $static) {
                Article::updateOrCreate(
                    ['slug' => $static->type],
                    [
                        'title' => $static->namevi ?: 'Untitled',
                        'content' => $static->contentvi,
                        'excerpt' => $static->descvi,
                        'type' => 'page',
                        'status' => $static->status === 'hienthi' ? 'published' : 'draft',
                        'published_at' => date('Y-m-d H:i:s', $static->date_created ?: time()),
                    ]
                );
            }
        }
    }

    protected function migrateMembers()
    {
        $this->info("Migrating members...");

        if (Schema::hasTable('table_member')) {
            $members = DB::table('table_member')->get();
            foreach ($members as $member) {
                $user = User::where('email', $member->email)
                    ->orWhere('username', $member->username)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $member->fullname ?: $member->username,
                        'username' => $member->username,
                        'email' => $member->email ?: ($member->username . '@legacy.com'),
                        'password' => $member->password ?: Hash::make('123456'),
                        'phone' => $member->phone,
                        'address' => $member->address,
                        'is_active' => $member->status === 'hienthi',
                        'email_verified_at' => now(),
                    ]);
                    $user->assignRole('member');
                }
            }
        }
    }
}
