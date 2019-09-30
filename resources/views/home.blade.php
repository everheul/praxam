@extends('layouts.exam')

{{--
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
<div class="container-fluid">
    <div class="row justify-content-left">
        <div class="col home-table">

            <div class="card w-100 home">
                <div class="card-body p-1">
                    <h1>Your Practice Exams</h1>
                </div>
            </div>

            <div class="card w-100 home mt-2">
                <div class="card-header py-1">
                    <h3>Exams In Progress</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Scenes</th>
                            <th>Started</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($working as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->scene_count }}</td>
                                <td>{{ $prax->created_at }}</td>
                                <td><a href="/prax/{{ $prax->id }}/next" class="btn btn-outline-secondary btn-sm">Continue</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100 home mt-2">
                <div class="card-header py-1">
                    <h3>Finished Exams</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Scenes</th>
                            <th>Finished</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hystory as $prax)
                            <tr>
                                <td>{{ $prax->exam->name }}</td>
                                <td>{{ $prax->scene_count }}</td>
                                <td>{{ $prax->finished_at }}</td>
                                <td>{{ $prax->result }}</td>
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
