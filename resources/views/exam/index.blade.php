{{-- exam.index
     input: $exams

--}}
@extends('layouts.exam')

@section('content')
    <div  class="container-fluid">
        <div class="card-columns" style="column-count: auto;">
                @foreach ($exams as $exam)
                <a href="/exam/{{ $exam->id }}" class="cardlink">
                    <div class="inlink">
                        <div class="fadeout"></div>
                        <div class="card-body">
                            <img src="{{ asset($exam->image) }}" alt="">
                            <h2 class="card-title">{{ $exam->name }}</h2>
                            <h4 class="card-title">{{ $exam->head }}</h4>
                            <p class="card-text">{{ $exam->intro }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
     </div>
@endsection

