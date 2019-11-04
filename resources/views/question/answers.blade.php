{{-- ANSWERS -- SORTABLE LIST
     input: $question
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Manage Answers</h3>
                <h4>Question {{ $question->order }} of {{ $question->scene->question_count }} </h4>
                <div class="card m-1 mt-3 px-3 py-1 border-dark"><h3>{{ $question->head }}</h3>{!! $question->text !!}</div>
                <hr />

                <div class="form-group row">
                    <label class="col-md-2 pt-2 control-label" for="submit">
                        Answers
                        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                                title="You can edit or delete answers here, and change their order by dragging them.">
                            <i class="fa fa-info" aria-hidden="true"></i>
                        </button>
                    </label>
                    <div class="col-md-10 pl-0">
                        <div id="answers_list" class="card p-1">

                        @foreach($question->answers as $answer)
                            <div class="dragable nots card px-3 py-1 m-2 bg-light" id="{{$answer->id}}">
                                <div class="row p-1">
                                    <div class="col-8 pr-0 pt-1 text-left">
                                        {{ $answer->getLabel() }}
                                    </div>
                                    <div class="col-4 pr-1">
                                        <form method="POST" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/{{ $answer->id }}/destroy" accept-charset="UTF-8">
                                            {{ csrf_field() }}
                                            <div class="btn-group btn-group-sm float-right" role="group">
                                                <button name="delete" type="submit" class="btn btn-danger" title="Delete Answer" onclick="return confirm(&quot;Click Ok to delete Answer.&quot;)">
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
                <div class="form-group row mb-3">
                    <label class="col-md-2 pt-2 control-label"></label>
                    <div class="col-md-10 pl-0">
                        @if($question->answers->count() > 1)
                            <form method="POST" id="answers_order" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/order" accept-charset="UTF-8">
                                {{ csrf_field() }}
                                <button name="save_order" type="submit" class="btn btn-primary" onclick="return saveOrder()">Save Order</button>
                            </form>
                        @endif
                    </div>
                </div>
                <hr />

                <div class="form-group row">
                    <label class="col-md-2 pt-2 control-label" for="submit">
                        Add Answers
                        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                                title="You can add one or more answers here; one on each line.">
                            <i class="fa fa-info" aria-hidden="true"></i>
                        </button>
                    </label>
                    <div class="col-md-10 pl-0">
                        <form method="POST" id="answer_txt" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/store" accept-charset="UTF-8">
                            {{ csrf_field() }}
                            <textarea name="answertxt" class="form-control w-100 mb-3" placeholder="Answer Text" rows="3" ></textarea>
                            <button name="add_answers" type="submit" class="btn btn-primary">Create Answers</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        // activate sortable on question list
        $('#answers_list').sortable({
                    cursor: "move",
                    scroll: false,
                    // stops page scolling if scrolled down:
                    create: function () {
                        $(this).height($(this).height());
                    }
                }
        )
    });

    function saveOrder() {
        var myForm = $("#answers_order");
        var order = 1;
        $("#answers_list .dragable").each(function () {
            var dom = document.createElement('input');
            dom.type = 'hidden';
            dom.name = 'answers[' + $(this).attr('id') + ']';
            dom.value = order++;
            myForm.append(dom);
        });
        return true;
    }

</script>
@endpush
