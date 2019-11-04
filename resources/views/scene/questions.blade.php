{{-- QUESTIONS -- SORTABLE LIST
  todo:
    - setup sortables to easily change the order of the questions
    - show / edit / delete buttins for all questions
 --}}

<h4>Scene Questions</h4>
<hr />
<div class="form-group row pb-3">
    <label class="col-md-2 pt-2 control-label" for="submit"></label>
    <div class="col-md-10 pl-0">
        <div id="question_list" class="card p-1">
            @foreach($scene->questions->sortBy('order') as $question)
                <div class="dragable nots card px-3 py-1 m-2 bg-light">
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
    <label class="col-md-2 pt-2 control-label"></label>
    <div class="col-md-10 pl-0">
        <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        // activate sortable on all type 3 questions:
        $('#question_list').sortable({
                cursor: "move",
                scroll: false,
                // stops page scolling if scrolled down:
                create: function () {
                    $(this).height($(this).height());
                }
            }
        )
    });
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