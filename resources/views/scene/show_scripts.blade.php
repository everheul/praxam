{{-- scene.show.scripts
     Javascripts codes for scene.show
     input: $praxscene
--}}

@push('scripts')
<script>
    var sc = { "id": {{ $praxscene->scene->id }}, "type": {{ $praxscene->scene->scene_type_id }} };
    var ql = {!! $praxscene->questionTypes() !!};

    /* register the buttons of all questions */
    $(function() {
        $.each(ql, function(index, obj){
            $("#done_" + obj.id).click(obj, doneClicked);
            if (obj.type == 3) {
                // activate sortable on all type 3 questions:
                $('#choices_' + obj.id + ', #answers_' + obj.id).sortable({
                        connectWith: '.sortable',
                        cursor: "move",
                        scroll: false,
                        create: function () {
                            $(this).height($(this).height());
                        }
                    }
                )
            }
        });
        // more questions; activate the accordion:
        if (sc.type == 2) {
            $("#accordion").accordion({
                active: {{ isset($question_order) ? $question_order : 0 }},
                heightStyle: "content",
                collapsible: true
            });
        }
    });

    function doneClicked(event) {
        /* the current question id and type are in event.data
        ** convert dragable items to answers */
        if (isAnswered(event.data.id, event.data.type)) {
            var myForm = $("#form_" + event.data.id);
            if (event.data.type == 3) {
                $("#answers_" + event.data.id + " .dragable").each(function(){
                    var dom = document.createElement('input');
                    dom.type = 'hidden';
                    dom.name = 'answer[]';
                    dom.value = $(this).attr('value');
                    myForm.append(dom);
                });
            }
            return true;
        } else {
            // use a popover to tell the user to give an answer first
            $(event.target).popover({ content: 'Please answer the question first.', trigger: 'manual' });
            $(event.target).popover('show');
            setTimeout(function() {
                $(event.target).popover('dispose');
                }, 2000);
            return false;
        }
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
