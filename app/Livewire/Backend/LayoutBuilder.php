<?php

namespace App\Livewire\Backend;

use App\Models\Page;
use App\Services\LayoutBuilder\BlockRegistry;
use Livewire\Component;
use Illuminate\Support\Str;

class LayoutBuilder extends Component
{
    public ?int $pageId = null;
    public string $pageTitle = '';
    public string $pageSlug = '';
    public bool $isActive = true;

    /** @var array Danh sách blocks hiện tại */
    public array $blocks = [];

    /** @var bool Hiển thị Modal chọn block */
    public bool $showBlockGallery = false;

    /** @var int|null Index block đang chỉnh sửa settings */
    public ?int $editingBlockIndex = null;

    /** @var array Dữ liệu settings của block đang chỉnh sửa */
    public array $editingBlockData = [];

    /** @var bool Modal xác nhận xóa */
    public bool $showDeleteModal = false;
    public ?int $deleteTargetIndex = null;

    /** @var bool Modal Preview */
    public bool $showPreview = false;

    public function mount(?int $pageId = null): void
    {
        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->pageId = $page->id;
            $this->pageTitle = $page->title;
            $this->pageSlug = $page->slug;
            $this->isActive = $page->is_active;
            $this->blocks = $page->layout_blocks ?? [];
        }
    }

    /**
     * Thêm một block mới vào danh sách.
     */
    public function addBlock(string $type): void
    {
        $registry = app(BlockRegistry::class);

        if (!$registry->has($type)) {
            return;
        }

        $this->blocks[] = [
            'id' => Str::uuid()->toString(),
            'type' => $type,
            'data' => $registry->getDefaults($type),
            'visible' => true,
        ];

        $this->showBlockGallery = false;
    }

    /**
     * Di chuyển block lên trên.
     */
    public function moveBlockUp(int $index): void
    {
        if ($index <= 0 || $index >= count($this->blocks)) {
            return;
        }

        $temp = $this->blocks[$index];
        $this->blocks[$index] = $this->blocks[$index - 1];
        $this->blocks[$index - 1] = $temp;
    }

    /**
     * Di chuyển block xuống dưới.
     */
    public function moveBlockDown(int $index): void
    {
        if ($index < 0 || $index >= count($this->blocks) - 1) {
            return;
        }

        $temp = $this->blocks[$index];
        $this->blocks[$index] = $this->blocks[$index + 1];
        $this->blocks[$index + 1] = $temp;
    }

    /**
     * Toggle hiển thị/ẩn block.
     */
    public function toggleBlockVisibility(int $index): void
    {
        if (isset($this->blocks[$index])) {
            $this->blocks[$index]['visible'] = !$this->blocks[$index]['visible'];
        }
    }

    /**
     * Mở settings panel cho block.
     */
    public function editBlock(int $index): void
    {
        if (isset($this->blocks[$index])) {
            $this->editingBlockIndex = $index;
            $this->editingBlockData = $this->blocks[$index]['data'];
        }
    }

    /**
     * Lưu settings cho block đang chỉnh sửa.
     */
    public function saveBlockSettings(): void
    {
        if ($this->editingBlockIndex !== null && isset($this->blocks[$this->editingBlockIndex])) {
            $type = $this->blocks[$this->editingBlockIndex]['type'];
            $registry = app(BlockRegistry::class);

            try {
                $validated = $registry->validate($type, $this->editingBlockData);
                $this->blocks[$this->editingBlockIndex]['data'] = $validated;
            } catch (\Throwable $e) {
                // Nếu validation thất bại, vẫn lưu data raw (admin tự chịu trách nhiệm)
                $this->blocks[$this->editingBlockIndex]['data'] = $this->editingBlockData;
            }

            $this->editingBlockIndex = null;
            $this->editingBlockData = [];
        }
    }

    /**
     * Hủy chỉnh sửa settings.
     */
    public function cancelEditBlock(): void
    {
        $this->editingBlockIndex = null;
        $this->editingBlockData = [];
    }

    /**
     * Xác nhận xóa block.
     */
    public function confirmDeleteBlock(int $index): void
    {
        $this->deleteTargetIndex = $index;
        $this->showDeleteModal = true;
    }

    /**
     * Xóa block.
     */
    public function deleteBlock(): void
    {
        if ($this->deleteTargetIndex !== null && isset($this->blocks[$this->deleteTargetIndex])) {
            array_splice($this->blocks, $this->deleteTargetIndex, 1);
        }

        $this->showDeleteModal = false;
        $this->deleteTargetIndex = null;
    }

    /**
     * Cập nhật thứ tự blocks từ SortableJS.
     */
    public function reorderBlocks(array $order): void
    {
        $reordered = [];
        foreach ($order as $index) {
            if (isset($this->blocks[$index])) {
                $reordered[] = $this->blocks[$index];
            }
        }
        $this->blocks = $reordered;
    }

    /**
     * Duplicate block.
     */
    public function duplicateBlock(int $index): void
    {
        if (isset($this->blocks[$index])) {
            $clone = $this->blocks[$index];
            $clone['id'] = Str::uuid()->toString();
            array_splice($this->blocks, $index + 1, 0, [$clone]);
        }
    }

    /**
     * Lưu toàn bộ layout vào Database.
     */
    public function save(): void
    {
        $this->validate([
            'pageTitle' => 'required|string|max:255',
        ]);

        $page = $this->pageId
            ? Page::findOrFail($this->pageId)
            : new Page();

        $page->title = $this->pageTitle;
        $page->slug = $this->pageSlug ?: Str::slug($this->pageTitle);
        $page->is_active = $this->isActive;
        $page->use_layout_builder = true;
        $page->layout_blocks = $this->blocks;
        $page->content = ''; // Layout builder thay thế content truyền thống
        $page->save();

        $this->pageId = $page->id;
        $this->pageSlug = $page->slug;

        session()->flash('success', 'Layout đã được lưu thành công!');
    }

    public function render()
    {
        $registry = app(BlockRegistry::class);

        return view('backend.layout-builder.index', [
            'blockGallery' => $registry->getByCategory(),
            'registeredBlocks' => $registry->all(),
        ])->layout('backend.layouts.app', ['title' => 'Layout Builder']);
    }
}


