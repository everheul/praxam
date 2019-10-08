{{-- examuser.show
     input: $praxexam,
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card w-100">
                <div class="examtext">
                    <h1 class="text-center">Test Result</h1>
                    <h3 class="text-center">{{ $praxexam->userexam->exam->name }}, {{ $praxexam->userexam->exam->head }}</h3>
                </div>
            </div>
            <div class="card w-100 mt-2">
                <div class="card-header py-1">
                    <h3>Scenes Overview</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Header</th>
                            <th>Questions</th>
                            <th>Max Score</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($praxexam->praxscenes as $praxscene)
                            <tr>
                                <td>{{ $praxscene->scene->head }}</td>
                                <td>{{ $praxscene->scene->question_count }}</td>
                                <td>{{ $praxscene->maxScore() }}</td>
                                <td>{{ $praxscene->score() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
