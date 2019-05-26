{{-- test.config
    sections: content
--}}
@extends('layouts.exam')

@push('style')

@endpush

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-4">
                <h1>Options</h1>
                <a href="/newtest/{{ $exam->id }}" class="cardlink">
                    <div class="inlink">
                        <div class="fadeout"></div>
                        <div class="card-body">
                            <img src="{{ asset($exam->image) }}" alt="">
                            <h2 class="card-title">Start the Test</h2>
                            <h4 class="card-title">{{ $exam->name }}</h4>
                            <p class="card-text">{{ $exam->head }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
