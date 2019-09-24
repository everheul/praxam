{{-- examuser.show
     input: $usertexam,
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card w-100">
                <div class="examtext">
                    <h1 class="text-center">Test Result</h1>
                    <h3 class="text-center">{{ $userexam->exam->name }}, {{ $userexam->exam->head }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
