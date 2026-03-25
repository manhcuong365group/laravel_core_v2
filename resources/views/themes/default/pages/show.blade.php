@extends('theme::layouts.app')

@section('title', $page->title . ' - ' . (app(\App\Services\TenantManager::class)->getTenant()->name ??
    config('app.name')))

@section('content')
    @if (isset($layoutHtml) && !empty($layoutHtml))
        {{-- Render blocks from Layout Builder --}}
        <div class="layout-builder-content">
            {!! $layoutHtml !!}
        </div>
    @else
        {{-- Default Page Content --}}
        <div class="bg-bg-main py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <header class="mb-16 text-center">
                        <h1 class="text-4xl md:text-7xl font-black tracking-tight mb-8 leading-tight">{{ $page->title }}
                        </h1>
                        <div class="w-24 h-2 bg-primary mx-auto rounded-full"></div>
                    </header>

                    <div class="glass-card p-8 md:p-16">
                        <div
                            class="prose prose-lg md:prose-xl prose-slate dark:prose-invert max-w-none prose-headings:font-black prose-a:text-primary leading-loose">
                            {!! $page->content !!}
                        </div>
                    </div>

                    <div class="mt-12 text-center text-text-muted text-sm">
                        Cập nhật lần cuối: {{ $page->updated_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
