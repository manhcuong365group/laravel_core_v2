@props(['model' => null, 'pageType' => null, 'seo' => null])

@php
    $title = config('app.name');
    $description = '';
    $keywords = '';
    $ogImage = null;

    if ($seo) {
        $title = $seo->title ?: $title;
        $description = $seo->meta_description ?: $description;
        $keywords = $seo->meta_keywords ?: $keywords;
        $ogImage = $seo->og_image;
    } elseif ($model) {
        $title = $model->title ?? ($model->name ?? $title);
        $description = $model->meta_description ?? ($model->excerpt ?? ($model->short_description ?? $description));
        $keywords = $model->meta_keywords ?? '';
        $ogImage = $model->getFirstMediaUrl('featured_image');
    }

    $title = strip_tags($title);
    $description = strip_tags($description);
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">

<!-- Facebook Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
@if ($ogImage)
    <meta property="twitter:image" content="{{ $ogImage }}">
@endif

<link rel="canonical" href="{{ url()->current() }}">
