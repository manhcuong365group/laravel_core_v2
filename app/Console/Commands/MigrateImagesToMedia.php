<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use App\Models\Article;
use App\Models\Page;
use App\Models\Category;
use App\Models\Brand;

class MigrateImagesToMedia extends Command
{
    protected $signature = 'images:migrate-to-media {--dry-run : Only log what would happen} {--force : Replace existing media}';
    protected $description = 'Migrate existing image paths from database columns to Spatie Media Library';

    public function handle()
    {
        $configs = [
            Product::class => 'featured_image',
            Article::class => 'featured_image',
            Page::class => 'featured_image',
            Category::class => 'image',
            Brand::class => 'logo',
        ];

        $stats = ['found' => 0, 'missing' => 0, 'success' => 0, 'error' => 0, 'skipped' => 0];

        foreach ($configs as $modelClass => $column) {
            $name = (new \ReflectionClass($modelClass))->getShortName();
            $this->info("Processing $name (column: $column)...");

            $models = $modelClass::whereNotNull($column)
                ->where($column, '!=', '')
                ->get();

            $total = $models->count();
            $this->info("Found $total records in DB.");

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($models as $model) {
                $path = $model->$column;

                // Normalize path (ensure no leading slash)
                $normalizedPath = ltrim($path, '/');

                // Fix common typos in DB paths
                $normalizedPath = str_replace('uploads/upload/', 'uploads/', $normalizedPath);

                $fullPath = public_path($normalizedPath);

                if (!File::exists($fullPath)) {
                    // Try strip "uploads/" if it's already in the path double
                    $altPath = str_replace('uploads/uploads/', 'uploads/', $normalizedPath);
                    $fullPathAlt = public_path($altPath);

                    if (File::exists($fullPathAlt)) {
                        $fullPath = $fullPathAlt;
                    } else {
                        $stats['missing']++;
                        $bar->advance();
                        continue;
                    }
                }

                $stats['found']++;

                if ($this->option('dry-run')) {
                    $bar->advance();
                    continue;
                }

                // Skip if already has media unless --force
                if ($model->hasMedia($column) && !$this->option('force')) {
                    $stats['skipped']++;
                    $bar->advance();
                    continue;
                }

                try {
                    $model->addMedia($fullPath)
                        ->preservingOriginal()
                        ->toMediaCollection($column);
                    $stats['success']++;
                } catch (\Exception $e) {
                    $stats['error']++;
                    $this->error("\nError migrating ID {$model->id}: " . $e->getMessage());
                }

                $bar->advance();
            }
            $bar->finish();
            $this->newLine(2);
        }

        $this->info("Migration completed!");
        $this->table(['Category', 'Count'], [
            ['Files Found', $stats['found']],
            ['Files Missing', $stats['missing']],
            ['Migrated Success', $stats['success'] + ($this->option('dry-run') ? $stats['found'] : 0)],
            ['Errors', $stats['error']],
            ['Skipped (Already exists)', $stats['skipped']],
            ['Total Missing (404)', $stats['missing']],
        ]);

        return 0;
    }
}
