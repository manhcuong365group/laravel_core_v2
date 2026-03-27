<?php

namespace App\Livewire\Backend\Articles;

use App\Actions\Article\UpdateArticleAction;
use App\Data\ArticleData;
use App\Models\Article;
use App\Traits\WithArticleForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads, WithArticleForms;

    public int $articleId;

    // Display data
    public ?string $currentImageUrl = null;
    public ?string $authorName = null;
    public ?string $createdAt = null;
    public int $viewCount = 0;

    public function mount(string $type, Article $article): void
    {
        $this->authorize('update', $article);
        
        $this->type = $type;
        $this->articleId = $article->id;

        $this->title = $article->title ?? '';
        $this->slug = $article->slug ?? '';
        $this->excerpt = $article->excerpt ?? '';
        $this->content = $article->content ?? '';
        $this->category_id = (string) ($article->category_id ?? '');
        $this->status = $article->status ?? 'draft';
        $this->published_at = $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '';
        $this->is_featured = (bool) $article->is_featured;
        $this->allow_comments = (bool) $article->allow_comments;
        $this->order = (string) ($article->order ?? '');
        $this->meta_title = $article->meta_title ?? '';
        $this->meta_description = $article->meta_description ?? '';
        $this->meta_keywords = $article->meta_keywords ?? '';
        $this->canonical_url = $article->canonical_url ?? '';

        // Display data
        $this->currentImageUrl = $article->getFirstMediaUrl('featured_image', 'thumb') ?: null;
        $this->authorName = $article->author?->name ?? 'System';
        $this->createdAt = $article->created_at?->format('Y-m-d H:i');
        $this->viewCount = $article->view_count ?? 0;
    }

    protected function rules(): array
    {
        return $this->articleRules($this->articleId);
    }

    public function save(UpdateArticleAction $action): void
    {
        $article = Article::findOrFail($this->articleId);
        $this->authorize('update', $article);

        $validated = $this->validate();

        try {
            $data = ArticleData::fromArray($validated);
            $action->execute($article, $data);

            session()->flash('success', $this->getSingularTitle() . ' đã được cập nhật!');
            $this->redirect(route('backend.articles.index', $this->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật bài viết!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.articles.edit-page', [
            'categories' => $this->getCategories(),
            'pageTitle' => 'Sửa: ' . $this->title,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa: ' . $this->title,
        ]);
    }
}
