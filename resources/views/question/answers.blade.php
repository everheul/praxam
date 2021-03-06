{{-- MANAGE ANSWERS -- SORTABLE LIST
     input: $question
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Manage Answers</h3>
                <h4>
                    Scene: {{ $question->scene->head }}
                    @if($question->scene->question_count > 1)
                        , Question {{ $question->order }} of {{ $question->scene->question_count }}
                    @endif
                </h4>
                <div class="card m-1 mt-3 px-3 py-1 border-dark"><h3>{{ $question->head }}</h3>{!! $question->text !!}</div>
                <hr />

                @if($question->answers->count() > 0)
                <div class="form-group row">
                    <label class="col-lg-2 pt-2 col-form-label" for="submit">
                        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                                title="You can edit or delete answers here, and change their order by dragging them.">
                            <i class="fa fa-info" aria-hidden="true"></i>
                        </button>
                        Answers
                    </label>
                    <div class="col-lg-5 mt-1">
                        <div class="card">
                            <div class="card-header p-2 text-center">
                                <h5 class="mb-0 mt-1">All Answers</h5>
                            </div>
                            <div id="all_answers" class="card-body p-1">
                {{-- all answers in std order --}}
                            @foreach($question->answers as $answer)
                                <div class="draggable nots card px-3 py-1 m-2 bg-light" id="answer_{{$answer->id}}">
                                    <div class="row p-1">
                                        <div class="col-10 text-left pt-1 px-1">
                                            <span name="a_label" class="text-secondary mr-1">Option {{ $answer->order }}: </span> <span name="a_text" class="text-dark"><b>{{ $answer->text }}</b></span>
                                        </div>
                                        <div class="col-2 text-right pr-1">
                                            <form method="POST" accept-charset="UTF-8" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/{{ $answer->id }}/destroy">
                                                @method('delete')
                                                @csrf
                                                <div class="btn-group btn-group-sm float-right" role="group">
                                                    <button name="delete" type="submit" class="btn btn-danger delete_answer" title="Delete Answer">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mt-1">
                        <div class="card">
                            <div class="card-header p-2 text-center">
                                <h5 class="mb-0 mt-1">{{ $question->questionType->name }}</h5>
                            </div>
                            <div id="correct_answers" class="card-body p-1">
                {{-- correct answers in correct order --}}
                            @foreach($correct as $answer)
                                <div class="draggable nots card px-3 py-1 m-2 bg-light" id="copy_answer_{{$answer->id}}">
                                    <div class="row p-1">
                                        <div class="col-10 text-left pt-1 px-1">
                                            <span name="a_label" class="text-secondary mr-1">Correct {{ $answer->correct_order }}: </span> <span name="a_text" class="text-dark"><b>{{ $answer->text }}</b></span>
                                        </div>
                                        <div class="col-2 text-right pr-1">
                                            <div class="btn-group btn-group-sm float-right" role="group">
                                                <button name="delete" type="submit" dragger="copy_answer_{{$answer->id}}" class="btn btn-danger remove_answer" title="Delete Answer">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col col-lg-2 col-form-label"></label>
                    <div class="col-md-10">
                        <form method="POST" id="answers_order" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/order" accept-charset="UTF-8">
                            {{ csrf_field() }}
                            <button name="save_stay" type="submit" class="btn btn-primary" onclick="return saveOrder()">Save & Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary" onclick="return saveOrder()">Save & Show Question</button>
                        </form>
                    </div>
                </div>
                <hr />
                @endif


                <div class="form-group row">
                    <label class="col-lg-2 col-form-label" for="submit">
                        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                                title="You can add one or more answers here; one on each line.">
                            <i class="fa fa-info" aria-hidden="true"></i>
                        </button>
                        Add Answers
                    </label>
                    <div class="col-lg-10">
                        <form method="POST" id="answer_txt" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/store" accept-charset="UTF-8">
                            {{ csrf_field() }}
                            <textarea name="answertxt" class="form-control w-100 mb-3" placeholder="Answer Text" rows="3" ></textarea>
                            <button name="save_stay" type="submit" class="btn btn-primary">Create & Stay</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var sortable_height;

    function deleteAnswer(e) {
        //console.log('deleting:',a);
        return confirm("Are you sure you want to delete this Answer?");
    }

    function removeAnswer(event) {
        event.stopPropagation();
        dragger = $(event.currentTarget).attr('dragger');
        console.log(event,dragger);
        $('#' + dragger).remove();
        return false;
    }
    
    function saveOrder() {
        var myForm = $("#answers_order");
        var order = 1;
        $("#all_answers .draggable").each(function () {
            var dom = document.createElement('input');
            dom.type = 'hidden';
            dom.name = 'answers[' + $(this).attr('id') + ']';
            dom.value = order++;
            myForm.append(dom);
        });
        order = 1;
        $("#correct_answers .draggable").each(function () {
            var dom = document.createElement('input');
            dom.type = 'hidden';
            dom.name = 'correct[' + $(this).attr('id') + ']';
            dom.value = order++;
            myForm.append(dom);
        });
        return true;
    }
</script>
@endpush

@push('ready')
    $('.delete_answer').on('click', deleteAnswer);
    $('.remove_answer').on('click', removeAnswer);
    // activate sortable on answer lists
    $('#all_answers').sortable({
        cursor: "move",
        scroll: false,
        // stops page scolling if scrolled down:
        create: function () {
            sortable_height = $(this).height();
            //$(this).height(sortable_height);
        },
        update: function () {
            sortable_height = $(this).height();
        },
        connectWith: "#correct_answers",
        forcePlaceholderSize: false,
        helper: function (e, li) {
            copyHelper = li.clone().insertAfter(li);
            return li.clone();
        },
        stop: function () {
            copyHelper && copyHelper.remove();
        }
    });
    $('#correct_answers').sortable({
        cursor: "move",
        scroll: false,
        create: function () {
            $(this).height(sortable_height);
        },
        update: function () {
            $(this).height(sortable_height);
        },
        receive: function (e, ui) {
            copyHelper = null;
            //- change the id:
            newid = 'copy_' + $(ui.item).attr('id');
            $(ui.item).attr('id', newid);
            //- change the label:
            $(ui.item).find("span[name=a_label]").text("Correct :");
            //- change click event target and add draggers id:
            $(ui.item).find("button").unbind('click').on('click', removeAnswer);
            $(ui.item).find("button").attr('dragger', newid);
        }
    });
    $('.btooltip').tooltip({ container: 'body', delay: { "show": 400, "hide": 100 } });
@endpush
