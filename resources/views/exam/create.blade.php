{{-- exam.create
     input: nope or $exam
--}}
@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="card w-100 p-3">
            <form method="post" action="/exam/store" enctype="multipart/form-data" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="nameid" class="col col-form-label">Exam Title *<p>± two words</p></label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="nameid" placeholder="Exam Title" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="form-group row{{ $errors->has('head') ? ' has-error' : '' }}">
                    <label for="headid" class="col col-form-label">Sub Title *<p>± five or six words</p></label>
                    <div class="col-sm-10">
                        <input name="head" type="text" class="form-control" id="headid" placeholder="Sub Title" value="{{ old('head') }}">
                    </div>
                </div>
                <div class="form-group row{{ $errors->has('intro') ? ' has-error' : '' }}">
                    <label for="introid" class="col col-form-label">Introduction<p>± thirty to fifty words</p></label>
                    <div class="col-sm-10">
                        <textarea name="intro" class="form-control" type="text" id="introid" rows="3" placeholder="Introduction">{{ old('intro') }}</textarea>
                    </div>
                </div>
                <div class="form-group row{{ $errors->has('newimage') ? ' has-error' : '' }}">
                    <label for="imageid" class="col col-form-label">Exam Image<p>± 500 pixels or more</p></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="show_image" placeholder="Select Image" disabled>
                        <input name="newimage" type="file" id="upload_image" value="{{ old('newimage') }}">
                    </div>
                </div>
                <div class="form-group row{{ $errors->has('text') ? ' has-error' : '' }}">
                    <label for="textid" class="col col-form-label">Description *<p>± as long as you need</p></label>
                    <div class="col-sm-10">
                        <textarea name="text" class="form-control" type="text" id="textid" rows="3" placeholder="Description">{{ old('text') }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="introid" class="col col-form-label"></label>
                    <div class="col-sm-10">
                        <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                        <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
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
        $('#upload_image').on('change',function () {
            $.each( $(this).prop("files"), function(k,v){
                $('#show_image').val( v['name'] );
            });
        });
    });
</script>
@endpush

