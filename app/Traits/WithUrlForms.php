<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait WithUrlForms
{
    // Form fields
    public string $title = '';
    public string $original_url = '';
    public string $short_url = '';
    public bool $is_active = true;
    public ?string $description = null;

    public function updatedOriginalUrl(string $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            if (empty($this->title)) {
                $this->fetchTitle();
            }
            if (empty($this->short_url)) {
                $this->short_url = Str::random(6);
            }
        }
    }

    // Search internal resources
    public string $internalSearch = '';
    public bool $showInternalSearch = false;

    public function toggleInternalSearch(): void
    {
        $this->showInternalSearch = !$this->showInternalSearch;
        if ($this->showInternalSearch) {
            $this->internalSearch = '';
        }
    }

    public function selectInternal(string $type, int $id, string $title, string $url): void
    {
        $this->title = $title;
        $this->original_url = $url;
        $this->showInternalSearch = false;
        $this->internalSearch = '';
        
        $this->dispatch('toast', message: "Đã lấy link từ " . ($type === 'product' ? 'Sản phẩm' : 'Tin bài'), type: 'success');
    }

    public function fetchTitle(): void
    {
        if (empty($this->original_url)) {
            $this->dispatch('toast', message: 'Vui lòng nhập URL gốc trước!', type: 'warning');
            return;
        }
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($this->original_url);
            if ($response->successful()) {
                $html = $response->body();
                if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
                    $this->title = trim(html_entity_decode($matches[1]));
                    $this->dispatch('toast', message: 'Đã lấy tiêu đề thành công!', type: 'success');
                } else {
                    $this->dispatch('toast', message: 'Không tìm thấy thẻ <title> trong URL này!', type: 'warning');
                }
            } else {
                $this->dispatch('toast', message: 'URL không phản hồi (HTTP ' . $response->status() . ')', type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Lỗi khi lấy dữ liệu: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Set up common URL rules.
     */
    protected function urlRules(mixed $urlId = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'original_url' => 'required|url',
            'short_url' => 'nullable|string|max:255|unique:urls,short_url' . ($urlId ? ',' . $urlId : ''),
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function getInternalResults()
    {
        if (empty($this->internalSearch)) {
            return [];
        }

        $articles = \App\Models\Article::where('title', 'like', "%{$this->internalSearch}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'type' => 'article',
                'id' => $item->id,
                'title' => $item->title,
                'url' => url(($item->type === 'page' ? '/trang/' : '/bai-viet/') . $item->slug),
                'icon' => $item->type === 'page' ? 'ti ti-file-text' : 'ti ti-news',
                'sub' => $item->type === 'page' ? 'Trang tĩnh' : 'Tin bài'
            ]);

        $products = \App\Models\Product::where('name', 'like', "%{$this->internalSearch}%")
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'type' => 'product',
                'id' => $item->id,
                'title' => $item->name,
                'url' => url('/san-pham/' . $item->slug), // Giả định domain front
                'icon' => 'ti ti-package',
                'sub' => 'Sản phẩm'
            ]);

        return $articles->concat($products);
    }
}
