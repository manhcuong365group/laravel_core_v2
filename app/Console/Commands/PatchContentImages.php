<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Article;
use App\Models\Page;
use Illuminate\Support\Facades\File;

class PatchContentImages extends Command
{
    protected $signature = 'images:patch-content {--dry-run}';
    protected $description = 'Migrate images inside HTML content to Spatie Media and update URLs';

    public function handle()
    {
        $models = [Product::class, Article::class, Page::class];
        $stats = ['total_images' => 0, 'patched' => 0, 'missing' => 0];

        foreach ($models as $modelClass) {
            $name = (new \ReflectionClass($modelClass))->getShortName();
            $this->info("Patching content for $name...");

            $items = $modelClass::where('content', 'LIKE', '%<img%')->get();
            $this->info("Found " . $items->count() . " records with images.");

            foreach ($items as $item) {
                $content = $item->content;
                $updated = false;

                // Match <img src="...">
                // We use a regex that handles both single and double quotes
                preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches);

                foreach ($matches[1] as $src) {
                    $stats['total_images']++;

                    // Check if it's a local path
                    // Usually contains 'uploads/'
                    if (str_contains($src, 'uploads/')) {
                        // Extract path relative to public/
                        // Handler for absolute URLs pointing to same domain
                        $relPath = parse_url($src, PHP_URL_PATH);
                        $relPath = ltrim($relPath, '/');

                        // Normalize
                        $relPath = str_replace('uploads/upload/', 'uploads/', $relPath);

                        // Skip if it's just a folder or invalid path
                        if (strlen($relPath) < 10 || is_dir(public_path($relPath))) {
                            continue;
                        }

                        $fullPath = public_path($relPath);

                        if (File::exists($fullPath)) {
                            if ($this->option('dry-run')) {
                                $this->line("Would patch image: $src -> " . $fullPath);
                                $stats['patched']++;
                                $updated = true;
                                continue;
                            }

                            try {
                                $media = $item->addMedia($fullPath)
                                    ->preservingOriginal()
                                    ->toMediaCollection('content_images');

                                $newUrl = $media->getUrl();
                                $content = str_replace($src, $newUrl, $content);
                                $updated = true;
                                $stats['patched']++;
                            } catch (\Exception $e) {
                                $this->error("Error patching image $src: " . $e->getMessage());
                            }
                        } else {
                            $stats['missing']++;
                        }
                    }
                }

                if ($updated && !$this->option('dry-run')) {
                    $item->update(['content' => $content]);
                }
            }
        }

        $this->info('Content patching completed!');
        $this->table(['Metric', 'Count'], [
            ['Total Images Found in HTML', $stats['total_images']],
            ['Successfully Patched', $stats['patched']],
            ['Missing Files (404)', $stats['missing']],
        ]);
    }
}
