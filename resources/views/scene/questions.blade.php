{{-- QUESTIONS -- SORTABLE LIST
 --}}

<div class="form-group row">
    <label class="col col-lg-2 col-form-label" for="submit">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                title="You can add, edit, view and delete this scenes' questions here, and change their order by dragging them.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Questions
    </label>
    <div class="col-lg-10">
        <div id="questions_list" class="card pt-2 pb-3 px-3">
            @foreach($scene->questions->sortBy('order') as $question)
                <div class="dragable card px-2 py-0 mt-2 mb-0 mx-0 appcolor" id="{{$question->id}}">
                    <div class="row p-1">
                        <div class="col-8 pr-0 pt-1 text-left">
                        {{ $question->getLabel() }}
                        </div>
                        <div class="col-4 pr-1">
                            <form method="POST" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/destroy" accept-charset="UTF-8">
                                {{ csrf_field() }}
                                <div class="btn-group btn-group-sm float-right" role="group">
                                    <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/show" class="btn btn-info" title="Show Question">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/edit" class="btn btn-primary" title="Edit Question">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <button name="delete" type="submit" class="btn btn-danger" title="Delete Question" onclick="return confirm(&quot;Click Ok to delete Question.&quot;)">
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
    <label class="col col-lg-2 col-form-label"></label>
    <div class="col-lg-10">
        <form method="POST" id="questions_order" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/order" accept-charset="UTF-8">
            {{ csrf_field() }}
            @if($scene->questions->count() < 9)
                <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
            @endif
            @if($scene->questions->count() > 1)
                <button name="save_order" type="submit" class="btn btn-primary ml-3" onclick="return saveOrder()">Save Order</button>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        // activate sortable on question list
        $('#questions_list').sortable({
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
        var myForm = $("#questions_order");
        var order = 1;
        $("#questions_list .dragable").each(function () {
                var dom = document.createElement('input');
                dom.type = 'hidden';
                dom.name = 'questions[' + $(this).attr('id') + ']';
                dom.value = order++;
                myForm.append(dom);
            });
        return true;
    }

</script>
@endpush

{{-- QUESTIONS - todo: sortable list
@foreach($scene->questions->sortBy('order') as $question)
    <div class="form-group row mb-0">
        <label class="col-md-2 pt-2 control-label">Question {{ $question->order }}</label>
        <div class="col-md-10 pl-0">
            <div class="card">
                <div class="card-header p-1">
                    {{ $question->head }}
                    <form method="POST" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/destroy" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <div class="btn-group btn-group-sm float-right" role="group">
                            <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/show" class="btn btn-info" title="Show Question">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/edit" class="btn btn-primary" title="Edit Question">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            <button name="delete" type="submit" class="btn btn-danger" title="Delete Question" onclick="return confirm(&quot;Click Ok to delete Question.&quot;)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-2">
                    {!! $question->text !!}
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="form-group row">
    <label class="col-md-2 pt-2 control-label"></label>
    <div class="col-md-10 pl-0">
        <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
    </div>
</div>



@foreach($scene->questions as $question)
    <div class="form-group row">
        <label class="col-md-2 pt-2 control-label">Question {{ $question->order }}</label>
        <div class="col pl-0">
            <div class="card">
                <div class="card-header p-1">
                    {{ $question->head }}
                    <form method="POST" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/destroy" accept-charset="UTF-8">
                        <input name="_method" value="DELETE" type="hidden">
                        {{ csrf_field() }}
                        <div class="btn-group btn-group-sm float-right" role="group">
                            <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/show" class="btn btn-info" title="Show Question">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <a href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/edit" class="btn btn-primary" title="Edit Question">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            <button type="submit" class="btn btn-danger" title="Delete Question" onclick="return confirm(&quot;Click Ok to delete Question.&quot;)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-2">
                    {!! $question->text !!}
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="form-group row">
    <label class="col-md-2 pt-2 control-label"></label>
    <div class="col-md-10 pl-0">
        <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
    </div>
</div>
--}}