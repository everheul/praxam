{{--
    HOME
    A complete overview of the (un-)finisched tests and created exams of this user.
    todo: show more usefull info
--}}

@extends('layouts.exam')

{{-- background image test
@push('style')
#content {
    background-image: url('/img/bgbird.jpg');
    background-picture: center;
    background-repeat: no-repeat;
    background-position: top;
    background-attachment: fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    background-size: cover;
    -o-background-size: cover;
}
@endpush
--}}
@section('content')
<div class="container">
    <div class="row justify-content-left">
        <div class="col home-table">

            <div class="card w-100 home headcolor">
                <div class="card-body p-1">
                    <h2 class="m-0">My Pracxam</h2>
                    <h4 class="m-0 text-secondary">Your Dashboard</h4>
                </div>
            </div>

            <div class="card w-100 home mt-3">
                <div class="card-header py-1 appcolor">
                    <h4>Tests In Progress</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Tested</th>
                            <th>Scenes</th>
                            <th>Started</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($working as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->scene_count }}</td>
                                <td>{{ date('d-m-Y', strtotime($prax->created_at)) }}</td>
                                <td><a href="/prax/{{ $prax->id }}/next" class="btn btn-outline-secondary btn-sm">Continue</a></td>
                                <td><a href="/prax/{{ $prax->id }}/destroy" class="btn btn-outline-secondary btn-sm" onclick="return confirm(&quot;Are you sure you want to delete this Test?&quot;)">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-3">
                <div class="card-header py-1 appcolor">
                    <h4>Finished Tests</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tested</th>
                                <th>Score</th>
                                <th>Hours Taken</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($hystory as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->resultStr() }}</td>
                                <td>{{ $prax->created_at->diffInHours($prax->finished_at) . ':' . $prax->created_at->diff($prax->finished_at)->format('%I:%S') }}</td>
                                <td><a href="/prax/{{ $prax->id }}/next" class="btn btn-outline-secondary btn-sm">Review</a></td>
                                <td><a href="/prax/{{ $prax->id }}/destroy" class="btn btn-outline-secondary btn-sm" onclick="return confirm(&quot;Are you sure you want to delete this Test?&quot;)">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-3">
                <div class="card-header py-1 appcolor">
                    <h4>Your Exams</h4>
                </div>
                 <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Testers</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($exams as $exam)
                            <tr>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->created_at }}</td>
                                <td>{{ $exam->user_count() }}</td>
                                <td><a href="/exam/{{ $exam->id }}/show" class="btn btn-outline-secondary btn-sm">Manage</a></td>
                                <td><a href="/exam/{{ $exam->id }}/destroy" class="btn btn-outline-secondary btn-sm" onclick="return confirm(&quot;Are you sure you want to delete this Exam?&quot;)">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
