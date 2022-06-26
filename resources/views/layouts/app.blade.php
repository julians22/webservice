<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head data-theme="cupcake">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('MEASURE_GA') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env('MEASURE_GA') }}');
    </script>
</head>
<body>
    <div id="app">
        <div class="container">
            <navigation></navigation>
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
