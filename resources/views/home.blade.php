@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(Auth::user()->isAdmin())
                    <div class="card-header">Admin Dashboard</div>
                    <div class="card-body">
                        todo
                    </div>
                @else
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        todo
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
