<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Article;
use App\Models\Page;
use App\Models\Category;
use App\Models\Brand;

class MediaCleanup extends Command
{
    protected $signature = 'media:cleanup {--force : Delete physical files}';
    protected $description = 'Clean up old image columns and orphaned files in public/uploads';

    public function handle()
    {
        $configs = [
            Product::class => 'featured_image',
            Article::class => 'featured_image',
            Page::class => 'featured_image',
            Category::class => 'image',
            Brand::class => 'logo',
        ];

        $this->info("Step 1: Nulling out migrated columns in database...");
        foreach ($configs as $modelClass => $column) {
            $name = (new \ReflectionClass($modelClass))->getShortName();
            // Only null out if model HAS media in that collection
            // This is safer than nulling everything
            $count = $modelClass::has('media')->update([$column => null]);
            $this->line(" - $name: Nullified $count records.");
        }

        $this->info("\nStep 2: Identifying files in public/uploads/images for deletion...");

        $basePath = public_path('uploads/images');
        if (!File::exists($basePath)) {
            $this->warn("Directory $basePath does not exist. Generic cleanup skipped.");
            return 0;
        }

        $allFiles = File::allFiles($basePath);
        $totalFiles = count($allFiles);
        $this->info("Found $totalFiles files in public/uploads/images.");

        if (!$this->option('force')) {
            $this->warn("To actually DELETE files, run with --force. Currently in report mode.");
        }

        $deletedCount = 0;
        $savedCount = 0;

        foreach ($allFiles as $file) {
            $relPath = 'uploads/images/' . $file->getRelativePathname();
            $relPath = str_replace('\\', '/', $relPath);

            // Check if this path is STILL used in DB (any record that didn't migrate)
            $isUsed = false;
            foreach ($configs as $modelClass => $column) {
                // Check if any record still has this path in the column
                // (Using LIKE or = depending on how path was stored)
                if ($modelClass::where($column, 'LIKE', "%$relPath%")->exists()) {
                    $isUsed = true;
                    break;
                }
            }

            if (!$isUsed) {
                if ($this->option('force')) {
                    File::delete($file->getRealPath());
                }
                $deletedCount++;
            } else {
                $savedCount++;
            }
        }

        $this->info("\nCleanup Summary:");
        $this->table(['Metric', 'Count'], [
            ['Files identified as unused/migrated', $deletedCount],
            ['Files still referenced in DB', $savedCount],
            ['Action', $this->option('force') ? 'DELETED' : 'Report Only'],
        ]);

        return 0;
    }
}
