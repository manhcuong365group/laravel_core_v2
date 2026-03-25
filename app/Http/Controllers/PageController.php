<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\LayoutBuilder\LayoutRendererService;
use Illuminate\View\View;


class PageController extends Controller
{
    public function __construct(
        protected LayoutRendererService $renderer
    ) {}

    /**
     * Hiển thị trang tĩnh hoặc trang xây dựng bằng Layout Builder.
     */
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Nếu trang sử dụng Layout Builder, render blocks
        if ($page->usesLayoutBuilder()) {
            $layoutHtml = $this->renderer->render($page->layout_blocks);

            return view('theme::pages.show', [
                'page' => $page,
                'layoutHtml' => $layoutHtml,
            ]);
        }

        // Ngược lại hiển thị nội dung mặc định (Rich Text)
        return view('theme::pages.show', [
            'page' => $page,
            'layoutHtml' => null,
        ]);
    }
}
