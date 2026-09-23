<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-light">
    @include('partials.navbar')
    @include('partials.alerts')
    @yield('content')
    @include('partials.footer')
    @include('partials.scripts')
    {{-- @include('partials.ai-widget') --}}
</body>
</html>
