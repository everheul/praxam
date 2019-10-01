{{-- scene.show.scripts
     Javascripts codes for scene.show
     input: $scene
--}}

@push('scripts')
<script>
    var sc = { "id": {{ $praxscene->scene->id }}, "type": {{ $praxscene->scene->scene_type_id }} };
    var ql = {!! $praxscene->questionTypes() !!};

    /* register the buttons of all questions */
    $(function() {
        // activate sortable on all type 3 questions:
        $.each(ql, function(index, obj){
            $("#done_" + obj.id).click(obj, doneClicked);
            if (obj.type == 3) {
                $('#choices_' + obj.id + ', #answers_' + obj.id).sortable({connectWith: '.sortable', cursor: "move"});
            }
        });
        // more questions; activate the accordion:
        if (sc.type == 2) {
            $("#accordion").accordion();
            $("#accordion").accordion({
                header: "ui-icon-triangle-1-e",
                activeHeader: "ui-icon-triangle-1-s",
                collapsible: true
            });
            //- skip earlier answered questions, if any:
            selectAccordionNext();
        }
    });

    function doneClicked(event) {
        /* the current question id and type are in event.data */
        if (isAnswered(event.data.id, event.data.type)) {
            myForm = $("#form_" + event.data.id);
            if (event.data.type == 3) {
                $("#answers_" + event.data.id + " .dragable").each(function(){
                    var dom = document.createElement('input');
                    dom.type = 'hidden';
                    dom.name = 'answer[]';
                    dom.value = $(this).attr('value');
                    myForm.append(dom);
                });
            }
            //myForm.submit();
            return true;
        } else {
            // tell the user to give an answer first
        }
        return false;
    }

    function selectAccordionNext() {
        var done = true;
        if (sc.type == 2) {
            var active = $('#accordion').accordion('option', 'active');
            $.each(ql, function(index, obj){
                // todo: activate the next unanswered question
                if ((done) && (index > active)) {
                    if ($("#done_" + obj.id).prop('disabled') != true) {
                        $("#accordion").accordion("option", 'active', index);
                        done = false;
                    }
                }
            });
        }
        return done;
    }

    function isAnswered(qid, qtype) {
        var ret = false;
        if (qtype == 1) {
            /* is one of the radioboxes checked? */
            $("question#" + qid + " :input[type='radio']:checked").each(function(){
                ret = true;
            });
        } else if (qtype == 2) {
            /* is one of the checkboxes checked? */
            $("question#" + qid + " :input[type='checkbox']:checked").each(function(){
                ret = true;
            });
        } else if (qtype == 3) {
            /* anything in the answers list? */
            $("#answers_" + qid + " .dragable").each(function(){
                ret = true;
            });
        }
        return ret;
    }

</script>
@endpush
