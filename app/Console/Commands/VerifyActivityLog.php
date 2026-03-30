<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyActivityLog extends Command
{
    protected $signature = 'dev:verify-activity-log';
    protected $description = 'Verify LogsActivity trait and WithBackendTable compliance across core components';

    public function handle(): void
    {
        $this->line('');
        $this->line('<fg=cyan>========================================</>');
        $this->line('<fg=cyan> MODULE STANDARD COMPLIANCE CHECK       </>');
        $this->line('<fg=cyan>========================================</>');

        $this->line('');
        $this->line('<fg=yellow>1. LogsActivity Trait (Models):</>');
        $this->checkActivityLog();

        $this->line('');
        $this->line('<fg=yellow>2. WithBackendTable Trait (Livewire IndexPages):</>');
        $this->checkBackendTable();

        $this->line('');
        $this->line('<fg=cyan>========================================</>');
        $this->line('');
    }

    private function checkActivityLog(): void
    {
        $trait = \Spatie\Activitylog\Traits\LogsActivity::class;
        $models = [
            \App\Models\Product::class,
            \App\Models\Category::class,
            \App\Models\Brand::class,
            \App\Models\Article::class,
        ];

        foreach ($models as $model) {
            $has = in_array($trait, class_uses_recursive($model));
            $label = class_basename($model);
            $this->line($has
                ? "  <fg=green>✅ {$label} — LogsActivity active</>"
                : "  <fg=red>❌ {$label} — MISSING LogsActivity</>");
        }
    }

    private function checkBackendTable(): void
    {
        $trait = \App\Traits\WithBackendTable::class;
        $pages = [
            \App\Livewire\Backend\Products\IndexPage::class   => 'Products/IndexPage',
            \App\Livewire\Backend\Categories\IndexPage::class => 'Categories/IndexPage',
            \App\Livewire\Backend\Brands\IndexPage::class     => 'Brands/IndexPage',
            \App\Livewire\Backend\Articles\IndexPage::class   => 'Articles/IndexPage',
        ];

        foreach ($pages as $class => $label) {
            $has = in_array($trait, class_uses_recursive($class));
            $this->line($has
                ? "  <fg=green>✅ {$label} — WithBackendTable active</>"
                : "  <fg=red>❌ {$label} — MISSING WithBackendTable</>");
        }
    }
}
