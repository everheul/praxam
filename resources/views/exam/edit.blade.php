{{-- exam.edit
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="card w-100 p-3">
            <form method="post" action="/exam/{{ $exam->id }}/update" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="id" type="hidden" value="{{ $exam->id }}">
                <div class="form-group row">
                    <label for="nameid" class="col col-form-label">Exam Title</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="nameid" placeholder="Exam Title" value="{{ $exam->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="headid" class="col col-form-label">Sub Title</label>
                    <div class="col-sm-10">
                        <input name="head" type="text" class="form-control" id="headid" placeholder="Sub Title" value="{{ $exam->head }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="introid" class="col col-form-label">Introduction</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="intro" type="text" id="introid" rows="3">{{ $exam->intro }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="imageid" class="col col-form-label">Image</label>
                    <div class="col-sm-10">
                        <input name="image" type="text" class="form-control" placeholder="Current Image" value="{{ $exam->image }}">
                        <input name="newimage" type="file" class="form-control-file m-1" id="imageid">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="textid" class="col col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="text" type="text" id="textid" rows="3">{{ $exam->text }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="introid" class="col col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}" type="application/javascript"></script>
<script>
    $( function() {
        CKEDITOR.replace( 'textid' );
    });
</script>
@endpush
