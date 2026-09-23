<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $pageTitle = trim($__env->yieldContent('title'));
    if ($pageTitle === '') {
        $inner = trim($__env->yieldContent('page_title'));
        $pageTitle = $inner !== '' ? $inner.' - MarketLink' : config('app.name', 'MarketLink');
    }
@endphp
<title>{{ $pageTitle }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
