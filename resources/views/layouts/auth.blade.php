<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ $clientAppName }}</title>

        <link rel="icon" href="{{ $clientFaviconUrl }}" type="image/png" />
        @yield('favicon')

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/sass/themes/dark/app-dark.scss', 'resources/sass/pages/auth.scss'])
    </head>
    <body>
        @vite(['resources/js/assets/js/initTheme.js'])

        <div id="auth">
            @yield('content')
        </div>
    </body>
</html>
