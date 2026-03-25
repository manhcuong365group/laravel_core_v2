<?php

namespace App\Services\LayoutBuilder;

use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

/**
 * Service render các blocks ra HTML cho frontend.
 * Có cơ chế fallback khi block bị lỗi dữ liệu.
 */
class LayoutRendererService
{
    public function __construct(
        protected BlockRegistry $registry
    ) {}

    /**
     * Render tất cả layout blocks của một page ra HTML.
     */
    public function render(array $blocks): HtmlString
    {
        $html = '';

        foreach ($blocks as $block) {
            $html .= $this->renderBlock($block);
        }

        return new HtmlString($html);
    }

    /**
     * Render một block đơn lẻ.
     * Nếu block lỗi, trả về HTML trống thay vì crash toàn trang.
     */
    public function renderBlock(array $block): string
    {
        try {
            $type = $block['type'] ?? null;
            $data = $block['data'] ?? [];
            $visible = $block['visible'] ?? true;

            if (!$visible || !$type) {
                return '';
            }

            $blockClass = $this->registry->get($type);

            if (!$blockClass) {
                return $this->renderFallback($type);
            }

            $viewName = $blockClass::viewName();

            if (!View::exists($viewName)) {
                return $this->renderFallback($type);
            }

            return View::make($viewName, [
                'data' => $data,
                'blockId' => $block['id'] ?? uniqid('block_'),
            ])->render();
        } catch (\Throwable $e) {
            report($e);
            return $this->renderFallback($block['type'] ?? 'unknown');
        }
    }

    /**
     * Fallback view khi block bị lỗi (chỉ hiển thị khi APP_DEBUG=true).
     */
    protected function renderFallback(string $type): string
    {
        if (!config('app.debug')) {
            return '';
        }

        return "<div class=\"bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800 rounded-lg my-2\">
            <strong>⚠ Block không hợp lệ:</strong> <code>{$type}</code>
        </div>";
    }
}
