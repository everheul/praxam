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
        $('.btooltip').tooltip({container: 'body'});
/*
        // handle delete answer click with ajax:
        $(".del-answer").on("click", function (e) {
            var answerId = $(this).attr("answerid");
            // todo: ask conformation
            // todo: send ajax delete, on OK call this:
            DeleteRow(this);
            return false;
        });

         // handle delete answer click with ajax:
         $(".add-answer").on("click", function (e) {
            var answerId = $(this).attr("answerid");
            // todo: send ajax command? or create post..?
            return false;
         });
*/
        //- disable enter key
        $("input:text").keypress(function(e){
            if(e.which == 13) {
                return false;
            }
        });

        //- copy the image name from invisible type=file input to visible text input
        $('#upload_image').on('change',function () {
            $.each( $(this).prop("files"), function(k,v){
                $('#show_image').val( v['name'] );
            });
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
