<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — 360° Virtual Tour</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Pannellum CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    @stack('styles')
    <style>
        body, html { margin: 0; padding: 0; background: #101914; overflow: hidden; }
        #tour-wrapper { position: fixed; inset: 0; }
        #panorama-container { width: 100%; height: 100%; }
    </style>
</head>
<body class="h-full overflow-hidden" x-data>
<div id="tour-wrapper">
    @yield('content')
</div>
@stack('scripts')
</body>
</html>
