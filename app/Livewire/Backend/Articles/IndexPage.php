<?php

namespace App\Livewire\Backend\Articles;

use App\Actions\Article\BulkDeleteArticleAction;
use App\Actions\Article\BulkStatusArticleAction;
use App\Actions\Article\DeleteArticleAction;
use App\Models\Article;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use \App\Traits\WithBackendTable;

    public string $type = 'post';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'categoryFilter' => ['except' => '', 'as' => 'category'],
    ];

    public function mount(string $type = 'post'): void
    {
        $this->authorize('viewAny', Article::class);
        $this->type = $type;
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getArticlesQuery()->get());
    }

    public function toggleFeatured(int $id): void
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);
        
        $article->update(['is_featured' => !$article->is_featured]);
        
        $this->notify('Đã cập nhật trạng thái nổi bật.');
    }

    public function toggleStatus(int $id): void
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);
        
        $newStatus = $article->status === 'published' ? 'draft' : 'published';
        $article->update(['status' => $newStatus]);
        
        $this->notify('Đã cập nhật trạng thái.');
    }

    public function updateField(int $id, string $field, $value): void
    {
        $this->executeUpdateField($id, $field, $value, Article::class);
    }

    public function executeDelete(DeleteArticleAction $deleteAction, BulkDeleteArticleAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Article::class, 'bài viết');
    }

    public function duplicate(int $id): void
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        $newArticle = $article->replicate();
        $newArticle->title = $article->title . ' (Copy)';
        $newArticle->status = 'draft';
        $newArticle->view_count = 0;
        $newArticle->is_featured = false;
        $newArticle->save();

        // Copy media
        foreach ($article->getMedia('featured_image') as $media) {
            $media->copy($newArticle, 'featured_image');
        }

        $this->notify("Đã sao chép bài viết '{$article->title}' thành công.");
    }

    public function bulkStatus(int $isActive, BulkStatusArticleAction $action): void
    {
        $this->executeBulkStatus($isActive, $action, 'bài viết');
    }

    private function getArticlesQuery()
    {
        return Article::query()
            ->where('type', $this->type)
            ->when($this->search, fn($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== '', fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getTitle(): string
    {
        return match ($this->type) {
            'post' => 'Quản lý bài viết',
            'news' => 'Quản lý tin tức',
            'page' => 'Quản lý trang tĩnh',
            'album' => 'Quản lý bộ sưu tập',
            default => 'Quản lý nội dung',
        };
    }

    #[Computed]
    public function categories()
    {
        return Category::where('type', $this->type)->get();
    }

    public function render()
    {
        $articles = $this->getArticlesQuery()
            ->with(['category', 'author'])
            ->paginate($this->perPage);

        return view('livewire.backend.articles.index-page', [
            'articles' => $articles,
            'categories' => $this->categories,
            'title' => $this->getTitle(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle(),
        ]);
    }
}

