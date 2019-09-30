{{-- scene.show.scripts
     Javascripts codes for scene.show
     input: $scene
--}}

@push('scripts')
<script>
    var sc = { "id": {{ $userscene->scene->id }}, "type": {{ $userscene->scene->scene_type_id }} };
    var ql = {!! $userscene->scene->questionTypes() !!};

    /* register the buttons of all questions */
    $(function() {
        // activate sortable on all type 3 questions:
        $.each(ql, function(index, obj){
        //    $("#done_" + obj.id).click(obj, doneClicked);
        //    $("#next_" + obj.id).click(obj, nextClicked);
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
/*
    function doneClicked(event) {
        // the current question id, type, first and last fields are in event.data
        if (isAnswered(event.data.id, event.data.type)) {
            sendAnswer(event.data.id, event.data.type);
        }
    }
*/
    //function nextClicked(event) {
    //    nextQuestion();
    //}
/*
    function isAnswered(qid, qtype) {
        var ret = false;
        if (qtype == 1) {
            // is one of the radioboxes checked?
            $("question#" + qid + " :input[type='radio']:checked").each(function(){
                ret = true;
            });
        } else if (qtype == 2) {
            // is one of the checkboxes checked?
            $("question#" + qid + " :input[type='checkbox']:checked").each(function(){
                ret = true;
            });
        } else if (qtype == 3) {
            // anything in the answers list?
            $("#answers_" + qid + " .dragable").each(function(){
                ret = true;
            });
        }
        return ret;
    }

    function isCorrect(qid, qtype) {
        var ret;
        if (qtype == 1) {
            ret = false;
            $("question#" + qid + " :input[type='radio']:checked").each(function(){
                //console.log($(this));
                ret = (isCool($(this).attr('value'), $(this).attr('iscool')) == 1);
            });
        } else if (qtype == 2) {
            ret = true;
            $("question#" + qid + " :input[type='checkbox']").each(function(){
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                var chkd = $(this).is(':checked') ? 1 : 0;
                if (cool != chkd) ret = false;
            });
        } else if (qtype == 3) {
            var order = 1;
            ret = true;
            $("#answers_" + qid + " .dragable").each(function(){
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                //console.log(this,cool);
                if (cool != order) {
                    ret = false;
                }
                order += 1;
            });
            //- anything still on the left?
            $("#choices_" + qid + " .dragable").each(function(){
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                if (cool != 0) {
                    ret = false;
                }
            });
        }
        return ret;
    }

    function sendAnswer(qid, qtype) {
        var answered = [];
        if (qtype == 1) {
            $("question#" + qid + " :input[type='radio']:checked").each(function(){
                answered.push($(this).attr('value'));
            });
        } else if (qtype == 2) {
            $("question#" + qid + " :input[type='checkbox']:checked").each(function(){
                answered.push($(this).attr('value'));
            });
        } else if (qtype == 3) {
            $("#answers_" + qid + " .dragable").each(function(){
                answered.push($(this).attr('value'));
            });
        }
        //- test ajaxo == null first?
        ajaxo = $.ajax({
            url: '/ajax',
            type: 'POST',
            data: { "action": "{{ $action }}",
                    "userexam": us.exam,
                    "order": us.order,
                    "scene": sc.id,
                    "question": qid,
                    "type": qtype,
                    "_token": "{{ csrf_token() }}",
                    "answers": answered
            },
            success: function(res){
                if (res == "OK") {
                    answerSent(qid, qtype);
                } else {
                    alert("Sorry, there is a problem: " + res);
                }
            },
            error: function(o,t,e) {
                console.log("error: ",o,t,e);
                alert("Sorry, there is a problem.");
            },
            complete: function() {
                ajaxo = null;
            }
        });
    }

    function isCool(b,c) {
        return parseInt(parseInt(c,16).toString().split("")[(parseInt(b)&7)+1]);
    }

    function answerSent(qid, qtype) {
        if (isCorrect(qid, qtype)) {
            nextQuestion();
        } else {
            showAnswer(qid, qtype);
        }
    }

    function showAnswer(qid, qtype) {
        $("#ëxpo_" + qid).toggle(true);
        $("#done_" + qid).prop('disabled', true);
        if (qtype == 1) {
            $("question#" + qid + " :input[type='radio']").each(function () {
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                $(this).parent('div').addClass( cool == 1 ? 'correct' : 'wrong' );
                $(this).prop('disabled', true);
            });
        } else if (qtype == 2) {
            $("question#" + qid + " :input[type='checkbox']").each(function(){
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                $(this).parent('div').addClass( cool == 1 ? 'correct' : 'wrong' );
                $(this).prop('disabled', true);
            });
        } else if (qtype == 3) {
            $("question#" + qid + " .sortable .dragable").each(function(){
                var cool = isCool($(this).attr('value'), $(this).attr('iscool'));
                if (cool > 0) {
                    $(this).addClass('correct');
                    $(this).prepend( cool + '. ');
                } else {
                    $(this).addClass('wrong');
                }
            });
            //- disable changes
            $("question#" + qid + " .sortable" ).sortable({ disabled: true });
        }
    }
*/
    function selectAccordionNext() {
        var done = true;
        if (sc.type == 2) {
            var active = $('#accordion').accordion('option', 'active');
            $.each(ql, function(index, obj){
                /* activate the next question NOT having the Done button disabled  */
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

</script>
@endpush
