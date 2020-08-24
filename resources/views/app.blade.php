<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">

    @yield('meta')

    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/icons/safari-pinned-tab.svg" color="#161616">
    <meta name="msapplication-TileColor" content="#161616">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{asset('css/app.css')}}?v={{filemtime('css/app.css')}}">

</head>

<body class="bg-black @yield('bodyclass')">

    {{-- @include('partials.cookieconsent') --}}

    <div id="app" class="font-default @yield('appclass') ">
        @include('partials.header')

        <main class="aw-main relative">
            @yield('content')
            @include('partials.footer')
        </main>

    </div>

    <script src="{{asset('js/app.js')}}?v={{filemtime('js/app.js')}}"></script>

    @include('partials.analytics')

</body>

</html>