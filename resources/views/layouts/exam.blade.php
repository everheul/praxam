{{-- layouts.exam
    base template for exam lists and questions.
    stacks: head, style, scripts
    sections: toprow, sidebar, content
    variables:
        $sidebar[
            ['type' => 'sbar-head','head','text'],
            ['type' => 'sbar-link','head','text','href'],
            ['type' => 'sbar-stat','todo'],
        ]
--}}

@extends('layouts.html')

@push('head')
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
@endpush

@section('body')
    <div id="mainapp" class="container-fluid">

        <!-- toprow -->
        @include('components.toprow',['align' => 'left'])

        <!-- sidebar -->
        @include('components.sidebar',$sidebar)

        <!-- Page Content -->
        <div id="content">
            @yield('content')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    $(document).ready(function () {
        $('.sidebar-arrow').on('click', function () {
            $('#sidebar').toggleClass('active');
            $('#content').toggleClass('active');
        });
    });
    </script>
@endpush
