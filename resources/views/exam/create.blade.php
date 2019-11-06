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
                @include('exam.form');
                <div class="form-group row">
                    <label for="introid" class="col col-form-label"></label>
                    <div class="col-sm-10">
                        <button name="save_edit" type="submit" class="btn btn-primary">Create Exam</button>
                        <a class="btn btn-primary ml-2 px-4" href="/home" role="button">Cancel</a>
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
    $(function() {
        CKEDITOR.replace( 'textid' );
        // display bootstrap tooltips:
        $('.btooltip').tooltip({ container: 'body', delay: { "show": 400, "hide": 100 } });
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
