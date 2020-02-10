{{-- layouts.html
    Mandatory! Every other view should extend this!
      @extends('layouts.html')

    stacks: head, style, scripts
    sections: body
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Praxam') }}</title>

    {{-- Fonts ? --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    {{-- styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/myapp.css') }}" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- any other lines for head? --}}
    @stack('head')
    {{-- temporary styles from other templates, clean up when ready! --}}
    <style>
        @stack('style')
    </style>
</head>
<body>
    <div id="app">
    @yield('body')
    </div>

    {{-- the jQuery, popper and bootstap scripts in one: --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- js code from other templates --}}
    @stack('scripts')

    <script>
        $(document).ready(function () {
            {{-- init code from other templates --}}
            @stack('ready')
        });
    </script>
</body>
</html>
