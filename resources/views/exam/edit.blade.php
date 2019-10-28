{{-- exam.edit
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="card w-100 p-3">
            <form method="post" action="/exam/{{ $exam->id }}/update" enctype="multipart/form-data" class="form-horizontal">
                {{ csrf_field() }}
                <input name="id" type="hidden" value="{{ $exam->id }}">
                <div class="form-group row">
                    <label for="nameid" class="col col-form-label">Exam Title *<p>± two words</p></label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="nameid" placeholder="Exam Title" value="{{ $exam->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="headid" class="col col-form-label">Sub Title *<p>± five or six words</p></label>
                    <div class="col-sm-10">
                        <input name="head" type="text" class="form-control" id="headid" placeholder="Sub Title" value="{{ $exam->head }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="introid" class="col col-form-label">Introduction<p>± thirty to fifty words</p></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="intro" type="text" id="introid" rows="3">{{ $exam->intro }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="imageid" class="col col-form-label">Exam Image<p>± 500 pixels or more</p></label>
                    <div class="col-sm-10">
                        <input name="image" type="text" class="form-control" placeholder="Select Image" id="show_image" value="{{ $exam->imageName() }}">
                        <input name="newimage" type="file" class="form-control-file m-1" id="upload_image">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="textid" class="col col-form-label">Description *<p>± as long as you need</p></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="text" type="text" id="textid" rows="3">{{ $exam->text }}</textarea>
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

@push('style')
@endpush

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

