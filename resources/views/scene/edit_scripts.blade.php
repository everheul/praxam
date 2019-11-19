{{-- scene.edit.scripts
     Javascripts codes for scene.show
     input: $scene
--}}

@push('scripts')
<script type="text/javascript" src="{{ asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>

    /* no need for CKEDITOR.replace ??
    $('textarea.ckeditor').each( function(){
        CKEDITOR.replace($(this).attr('id'));
    })
    */

    $(function () {
        // display bootstrap tooltips:
        $('.btooltip').tooltip({ container: 'body', delay: { "show": 400, "hide": 100 } });

        //- disable enter key
        $("input:text").keypress(function(e){
            if (e.which == 13) {
                return false;
            }
        });

        // show selected image name (bootstrap style):
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

    });


    //- by Jacob Stamm
    function DeleteRow(cellButton) {
        var row = $(cellButton).closest('tr').children('td').css({ backgroundColor: "red", color: "white" });
        setTimeout(function () {
            $(row).animate({ paddingTop: 0, paddingBottom: 0 }, 500)
                  .wrapInner('<div />')
                  .children()
                  .slideUp(500, function() { $(this).closest('tr').remove(); });
        }, 350 );
    };

</script>
@endpush
