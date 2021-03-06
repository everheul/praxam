{{-- layouts.app
    base template for topbar-only pages like welcome and login.
    sections: style, content, script
--}}

@extends('layouts.html')

@push('head')
<link href="{{ asset('css/nosidebar.css') }}" rel="stylesheet">
@endpush

@section('body')
    <div id="mainapp">

        <!-- toprow -->
        @include('layouts.toprow')

         <!-- Page Content -->
        <div id="content">
            @yield('content')
        </div>
    </div>
@endsection
