{{--
     exam.show
     input: $exam
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100">
                <div class="card-header headcolor pb-0 pt-2 pl-1">
                    <div class="exam_cardhead text-muted">Owner: <span class="ml-2 text-dark"> {{ $exam->owner() }}</span></div>
                    <div class="exam_cardhead text-muted">Scenes: <span class="ml-2 text-dark"> {{ $exam->scene_count }}</span></div>
                    <div class="exam_cardhead text-muted">Created:  <span class="ml-2 text-dark"> {{ $exam->created_at->format('d-m-Y') }}</span></div>
                    <div class="exam_cardhead text-muted">Changed:  <span class="ml-2 text-dark"> {{ $last_change }}</span></div>
                    <div class="exam_cardhead text-muted">Testers:  <span class="ml-2 text-dark"> todo </span></div>
                    <div class="exam_cardhead text-muted">Avg. Score:  <span class="ml-2 text-dark"> todo </span></div>
                </div>
                <div class="examtext card-body">
                    <img class="card-img-left" src="{{ asset($exam->image) }}" alt="">
                    <h1>{{ $exam->name }}</h1>
                    <h2>{{ $exam->head }}</h2>
                    <p>{{ $exam->intro }}</p>
                    {!! $exam->text !!}
                </div>
                <div class="card-footer appcolor text-center">
                    @if($exam->is_public)
                    <a class="btn btn-primary" href="/prax/{{ $exam->id }}/create" role="button" id="add_scene">Test Your Knowledge</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
