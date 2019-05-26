{{-- exam.show
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card">
                <div class="examtext">
                    <img class="card-img-left" src="{{ asset($exam->image) }}" alt="">
                    <h1>{{ $exam->name }}</h1>
                    <h2>{{ $exam->head }}</h2>
                    <p>{{ $exam->intro }}</p>
                    {!! $exam->text !!}
                </div>
            </div>
        </div>
    </div>
@endsection
