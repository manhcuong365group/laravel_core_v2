<?php

namespace App\Livewire\Backend\Settings;

use App\Models\SeoPage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SeoPagesEdit extends Component
{
    use WithFileUploads;

    public $pageType;
    public $seoPage;

    public $title;
    public $meta_description;
    public $meta_keywords;
    public $og_title;
    public $og_description;
    public $og_image_url;
    public $og_image_file;
    public $current_og_image;
    public $twitter_card;
    public $schema_markup;
    public $custom_head;
    public $custom_body;

    public function mount($pageType)
    {
        $this->pageType = $pageType;
        $this->seoPage = SeoPage::where('page_type', $pageType)->firstOrNew([
            'page_type' => $pageType,
        ]);

        $this->title = $this->seoPage->title;
        $this->meta_description = $this->seoPage->meta_description;
        $this->meta_keywords = $this->seoPage->meta_keywords;
        $this->og_title = $this->seoPage->og_title;
        $this->og_description = $this->seoPage->og_description;
        $this->current_og_image = $this->seoPage->og_image;
        $this->twitter_card = $this->seoPage->twitter_card;
        $this->schema_markup = $this->seoPage->schema_markup ? json_encode($this->seoPage->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $this->custom_head = $this->seoPage->custom_head;
        $this->custom_body = $this->seoPage->custom_body;
    }

    public function update()
    {
        $this->validate([
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image_url' => 'nullable|string|max:500',
            'og_image_file' => 'nullable|image|max:2048',
            'twitter_card' => 'nullable|string|max:50',
            'schema_markup' => 'nullable',
            'custom_head' => 'nullable|string',
            'custom_body' => 'nullable|string',
        ]);

        $ogImage = $this->og_image_url ?: $this->current_og_image;
        if ($this->og_image_file) {
            $fileName = 'seo_' . $this->pageType . '_' . time() . '.' . $this->og_image_file->getClientOriginalExtension();
            $path = $this->og_image_file->storeAs('seo', $fileName, 'public');
            $ogImage = Storage::url($path);
        }

        $schemaMarkup = null;
        if ($this->schema_markup) {
            $schemaMarkup = json_decode($this->schema_markup, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('schema_markup', 'JSON không hợp lệ.');
                return;
            }
        }

        $this->seoPage->fill([
            'title' => $this->title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $ogImage,
            'twitter_card' => $this->twitter_card,
            'schema_markup' => $schemaMarkup,
            'custom_head' => $this->custom_head,
            'custom_body' => $this->custom_body,
        ]);

        $this->seoPage->save();

        $this->dispatch('toast', message: 'SEO đã được cập nhật!', type: 'success');
        return redirect()->route('backend.settings.seo-pages');
    }

    public function render()
    {
        return view('livewire.backend.settings.seo-pages-edit')
            ->layout('backend.layouts.app', [
                'title' => 'SEO: ' . $this->getPageTypeName($this->pageType)
            ]);
    }

    protected function getPageTypeName(string $pageType): string
    {
        return match ($pageType) {
            'home' => 'Trang chủ',
            'products' => 'Trang sản phẩm',
            'articles' => 'Trang bài viết',
            'contact' => 'Trang liên hệ',
            default => ucfirst($pageType),
        };
    }
}


