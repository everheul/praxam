{{-- exam.edit_scripts
     Javascripts codes for: exam.edit/create, scene.{type}.edit/create, question.{type}.edit/create
--}}
@push('scripts')
<script type="text/javascript" src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
@endpush

@push('ready')
    // replace all textarea's with ckeditor class (if any):
    CKEDITOR.replaceAll( 'ckeditor' );

    // display bootstrap tooltips (if any):
    $('.btooltip').tooltip({ container: 'body', delay: { "show": 400, "hide": 100 } });

    //- disable enter key
    $("input:text").keypress(function(e){
        if (e.which == 13) {
            return false;
        }
    });

    // alter upload file input (if any) - show selected image name (bootstrap4):
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
@endpush
