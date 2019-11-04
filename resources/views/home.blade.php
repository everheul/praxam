{{--
    HOME
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
                    <h2>My Pracxam</h2>
                    <h4>Your Tests and Exams</h4>
                </div>
            </div>

            <div class="card w-100 home mt-2">
                <div class="card-header py-1 appcolor">
                    <h3>Tests In Progress</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Started</th>
                            <th>Scenes</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($working as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->created_at }}</td>
                                <td>{{ $prax->scene_count }}</td>
                                <td><a href="/prax/{{ $prax->id }}/next" class="btn btn-outline-secondary btn-sm">Continue</a></td>
                                <td><a href="/prax/{{ $prax->id }}/destroy" class="btn btn-outline-secondary btn-sm" onclick="return confirm(&quot;Are you sure you want to delete this Test?&quot;)">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-2">
                <div class="card-header py-1 appcolor">
                    <h3>Finished Tests</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Finished</th>
                            <th>Score</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hystory as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->finished_at }}</td>
                                <td>{{ $prax->result }}</td>
                                <td><a href="/prax/{{ $prax->id }}/next" class="btn btn-outline-secondary btn-sm">Explore</a></td>
                                <td><a href="/prax/{{ $prax->id }}/destroy" class="btn btn-outline-secondary btn-sm" onclick="return confirm(&quot;Are you sure you want to delete this Test?&quot;)">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-2">
                <div class="card-header py-1 appcolor">
                    <div class="container">
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6"><h3>Your Exams</h3></div>
                            <div class="col-3">
                                <div class="btn-group btn-group-sm float-right" role="group">
                                    <a href="/exam/create" class="btn btn-success" title="Create New Exam">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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

{{--
            @if(Auth::user()->isAdmin())
                <div class="card w-100 home mt-2">
                    <div class="card-header py-1">
                        <h3>Admin Dashboard</h3>
                </div>
                    <div class="card-body">
                        todo
                    </div>
                </div>
            @endif
--}}
        </div>
    </div>
</div>
@endsection
