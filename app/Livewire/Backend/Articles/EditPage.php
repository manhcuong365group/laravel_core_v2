<?php

namespace App\Livewire\Backend\Articles;

use App\Actions\Article\UpdateArticleAction;
use App\Data\ArticleData;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads;

    public string $type = 'post';
    public int $articleId;

    // Form fields
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $content = '';
    public string $category_id = '';
    public string $status = 'draft';
    public string $published_at = '';
    public bool $is_featured = false;
    public bool $allow_comments = true;
    public string $order = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public string $canonical_url = '';
    public $featured_image;

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
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $this->articleId,
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'featured_image' => 'nullable|image|max:2048',
        ];
    }

    public function save(UpdateArticleAction $action): void
    {
        $validated = $this->validate();

        $article = Article::findOrFail($this->articleId);
        $this->authorize('update', $article);

        $data = ArticleData::fromArray($validated, $this->type);
        $action->execute($article, $data);

        session()->flash('success', $this->getSingularTitle() . ' đã được cập nhật!');
        $this->redirect(route('backend.articles.index', $this->type), navigate: true);
    }

    public function getSingularTitle(): string
    {
        return match ($this->type) {
            'post' => 'Bài viết',
            'news' => 'Tin tức',
            'page' => 'Trang tĩnh',
            default => 'Nội dung',
        };
    }

    public function getCategories(): Collection
    {
        return Category::ofType($this->type === 'page' ? 'page' : 'news')
            ->active()
            ->orderBy('name')
            ->get();
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


