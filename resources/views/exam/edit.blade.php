{{-- exam.edit
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="card w-100 p-3 appcolor">
            <h3>Edit Exam</h3>
            <hr />
            <form method="post" action="/exam/{{ $exam->id }}/update" enctype="multipart/form-data" class="form-horizontal">
                {{ csrf_field() }}
                <input name="id" type="hidden" value="{{ $exam->id }}">

                @include('exam.form');

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
        // replace textarea with ckeditor:
        CKEDITOR.replace( 'textid' );

        // display bootstrap tooltips:
        $('.btooltip').tooltip({ container: 'body', delay: { "show": 400, "hide": 100 } });
    });
</script>
@endpush

