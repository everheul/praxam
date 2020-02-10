{{-- layouts.exam
    Base template for all pages but welcome.
    input: $sidebar
--}}

@extends('layouts.html')

@push('head')
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
@endpush

@section('body')
    <div id="mainapp" class="container-fluid clearfix">

        <!-- toprow -->
        @include('layouts.toprow')

        <!-- sidebar -->
        @include('layouts.sidebar',$sidebar)

        <!-- page content -->
        <div id="content">
            @yield('content')
        </div>
    </div>
@endsection
