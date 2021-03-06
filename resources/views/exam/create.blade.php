{{-- exam.create
     input: $exam (may be null)
--}}
@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="card w-100 p-3 appcolor">
            <h3>Create New Exam</h3>
            <hr />
            <form method="post" action="/exam/store" enctype="multipart/form-data" class="form-horizontal">
                {{ csrf_field() }}
                @include('exam.form')
                <div class="form-group row">
                    <label for="introid" class="col col-md-2 col-form-label"></label>
                    <div class="col-md-10">
                        <button name="save_edit" type="submit" class="btn btn-primary">Create Exam</button>
                        <a class="btn btn-outline-primary ml-3 px-4" href="/home" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('exam.edit_scripts')
