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

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    {{-- bootstrap styles: --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- any other lines for head? --}}
    @stack('head')

    {{-- temporary style thing, clean up when ready! --}}
    <style>
        @stack('style')
        body {
            background: #f8fff6;
        }
    </style>
</head>
<body>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div id="app">
    @yield('body')
    </div>

    {{-- the jQuery, popper and bootstap scripts in one: --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- scripts stack, script code from other templates --}}
    @stack('scripts')
</body>
</html>
