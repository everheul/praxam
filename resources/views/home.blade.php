{{--
    HOME
    A complete overview of the (un-)finisched tests and created exams of this user.
    todo: show more usefull info
--}}

@extends('layouts.exam')

@push('style')

@endpush

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
                <div class="card-header pt-2 pb-0 headcolor">
                    <h5>Tests In Progress</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="appcolor">
                            <tr>
                                <th>Tested</th>
                                <th>Scenes</th>
                                <th>Started</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($working as $prax)
                            <tr>
                                <td><div class="tbltxt">{{ $prax->exam->name }}</div></td>
                                <td><div class="tbltxt">{{ $prax->scene_count }}</div></td>
                                <td><div class="tbltxt">{{ date('d-m-Y', strtotime($prax->created_at)) }}</div></td>
                                <td>
                                    <form method="POST" action="/prax/{{ $prax->id }}/destroy" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <div class="btn-group btn-group-sm float-right" role="group">
                                            <a href="/prax/{{ $prax->id }}/next" class="btn btn-info bg-light" title="Continue Test">
                                                <i class="fa fa-play-circle" aria-hidden="true"></i>
                                            </a>
                                            <button name="delete" type="submit" class="btn btn-danger ml-a" title="Delete Test" onclick="return confirm(&quot;Are you sure you want to delete this test?&quot;)">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-3">
                <div class="card-header pt-2 pb-0 headcolor">
                    <h5>Finished Tests</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="appcolor">
                            <tr>
                                <th>Tested</th>
                                <th>Score</th>
                                <th>Hours Taken</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($hystory as $prax)
                            <tr>
                                <td><div class="tbltxt">{{ $prax->exam->name }}</div></td>
                                <td><div class="tbltxt">{{ $prax->resultStr() }}</div></td>
                                <td><div class="tbltxt">{{ $prax->created_at->diffInHours($prax->finished_at) . ':' . $prax->created_at->diff($prax->finished_at)->format('%I:%S') }}</div></td>
                                <td>
                                    <form method="POST" action="/prax/{{ $prax->id }}/destroy" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <div class="btn-group btn-group-sm float-right" role="group">
                                            <a href="/prax/{{ $prax->id }}/next" class="btn btn-info bg-light" title="Review Test">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <button name="delete" type="submit" class="btn btn-danger ml-a" title="Delete Test" onclick="return confirm(&quot;Are you sure you want to delete this test?&quot;)">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-3">
                <div class="card-header pt-2 pb-0 headcolor">
                    <h5>Your Exams</h5>
                </div>
                 <div class="card-body">
                     <table class="table table-hover table-sm mb-0">
                         <thead class="appcolor">
                            <tr>
                                <th></th>
                                <th>Index</th>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Testers</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($exams as $exam)
                            <tr>
                                <td>
                                    <div class="btn-group btn-group-sm float-left" role="group">
                                        <span class="btn btn-info {{ ($exam->is_valid) ? ($exam->is_public) ? 'bg-success' : 'bg-warning' : 'bg-danger' }}"
                                              title="{{ ($exam->is_valid) ? ($exam->is_public) ? 'Published' : 'Not Published' : 'Not Valid' }}">
                                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </td>
                                <td><div class="tbltxt">{{ $exam->id }}</div></td>
                                <td><div class="tbltxt">{{ $exam->name }}</div></td>
                                <td><div class="tbltxt">{{ $exam->created_at->format('d-m-Y') }}</div></td>
                                <td><div class="tbltxt">{{ $exam->user_count() }}</div></td>
                                <td>
                                    <form method="POST" action="/exam/{{ $exam->id }}/destroy" accept-charset="UTF-8">
                                        {{ csrf_field() }}
                                        <div class="btn-group btn-group-sm float-right" role="group">
                                            <a href="/exam/{{ $exam->id }}/show" class="btn btn-info bg-light ml-a" title="Show Exam">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="/exam/{{ $exam->id }}/edit" class="btn btn-primary ml-a" title="Edit Exam">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="/exam/{{ $exam->id }}/scene" class="btn btn-secondary ml-a" title="Manage Scenes">
                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                            </a>
                                            <button name="delete" type="submit" class="btn btn-danger ml-a" title="Delete Exam" onclick="return confirm(&quot;Are you sure you want to delete this exam?&quot;)">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
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
