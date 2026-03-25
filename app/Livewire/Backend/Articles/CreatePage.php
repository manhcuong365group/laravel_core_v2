<?php

namespace App\Livewire\Backend\Articles;

use App\Actions\Article\CreateArticleAction;
use App\Data\ArticleData;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads;

    public string $type = 'post';

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

    public function mount(string $type = 'post'): void
    {
        $this->authorize('create', Article::class);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
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

    public function save(CreateArticleAction $action): void
    {
        $validated = $this->validate();

        $data = ArticleData::fromArray($validated, $this->type);
        $action->execute($data);

        session()->flash('success', $this->getSingularTitle() . ' đã được tạo thành công!');
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
        return view('livewire.backend.articles.create-page', [
            'categories' => $this->getCategories(),
            'pageTitle' => 'Thêm ' . $this->getSingularTitle(),
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm ' . $this->getSingularTitle(),
        ]);
    }
}


