<?php

namespace App\Livewire\Backend\Articles;

use App\Actions\Article\CreateArticleAction;
use App\Data\ArticleData;
use App\Models\Article;
use App\Traits\WithArticleForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads, WithArticleForms;

    public function mount(string $type = 'post'): void
    {
        $this->authorize('create', Article::class);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return $this->articleRules();
    }

    public function save(CreateArticleAction $action): void
    {
        $validated = $this->validate();

        try {
            $data = ArticleData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', $this->getSingularTitle() . ' đã được tạo thành công!');
            $this->redirect(route('backend.articles.index', $this->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo bài viết!', type: 'error');
        }
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
