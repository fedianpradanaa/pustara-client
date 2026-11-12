<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="description" content="{{ $site['og_description'] }}">
        <meta property="og:site_name" content="{{ $site['app_name'] }}">
        <meta property="og:title" content="{{ $site['og_title'] }}" />
        <meta property="og:description" content="{{ $site['og_description'] }}" />
        @if ($site['og_image'] !== '')
            <meta property="og:image" itemprop="image" content="{{ $site['og_image'] }}" />
        @endif
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $site['og_title'] }}">
        <meta name="twitter:description" content="{{ $site['og_description'] }}">
        @if ($site['og_image'] !== '')
            <meta name="twitter:image" content="{{ $site['og_image'] }}">
        @endif
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ $site['favicon_url'] }}">
        <link rel="apple-touch-icon" href="{{ $site['favicon_url'] }}">
        <meta name="theme-color" content="#ffffff">

        <title>{{ $site['app_name'] }}</title>

        <link rel="shortcut icon" href="{{ $site['favicon_url'] }}" type="image/png" />

        <script type="text/javascript">
            if (window.top !== window.self) {
                window.top.location = window.self.location;
            }
        </script>
        @yield('favicon')

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/sass/iconly.scss', 'resources/sass/themes/dark/app-dark.scss', 'resources/sass/pages/chat.scss', 'resources/sass/pages/datatables.scss', 'resources/sass/pages/dripicons.scss', 'resources/sass/pages/email.scss', 'resources/sass/pages/error.scss', 'resources/sass/pages/flag.scss', 'resources/sass/pages/form-element-select.scss', 'resources/sass/pages/simple-datatables.scss', 'resources/sass/pages/summernote.scss', 'resources/sass/pages/sweetalert2.scss', 'resources/js/main.js'])
    </head>
    <body>
        @vite(['resources/js/assets/js/initTheme.js'])

        <div id="usr-main-container">
            @yield('content')
        </div>

        @vite(['resources/js/assets/js/components/dark.js', 'resources/js/assets/js/pages/horizontal-layout.js'])
    </body>
</html>
