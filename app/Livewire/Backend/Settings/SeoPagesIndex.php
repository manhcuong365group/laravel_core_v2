<?php

namespace App\Livewire\Backend\Settings;

use App\Models\SeoPage;
use Livewire\Component;

class SeoPagesIndex extends Component
{
    public function render()
    {
        $seoPages = SeoPage::all();

        return view('livewire.backend.settings.seo-pages-index', [
            'seoPages' => $seoPages,
        ])->layout('backend.layouts.app', ['title' => 'SEO Trang']);
    }
}

