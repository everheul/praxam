@extends('layouts.app')

@push('style')
    .content {
        text-align: center;
    }
    .title {
        font-size: 94px;
    }
@endpush

@section('content')
    <div class="container content">
        <div class="title m-b-md">
            Praxam
        </div>
        <div class="links">
            <a href="/exam">Our Practice Exams</a>
        </div>
    </div>
@endsection


