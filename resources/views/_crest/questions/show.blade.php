@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5  class="my-1 float-left">{{ isset($title) ? $title : 'Question' }}</h5>
        <div class="float-right">
            <form method="POST" action="{!! route('crest.questions.destroy', $question->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('crest.questions.index') }}" class="btn btn-primary" title="Show All Question">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('crest.questions.create') }}" class="btn btn-success" title="Create New Question">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                    
                    <a href="{{ route('crest.questions.edit', $question->id ) }}" class="btn btn-primary" title="Edit Question">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Question" onclick="return confirm(&quot;Click Ok to delete Question.?&quot;)">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Index</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->id }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Scene</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ optional($question->scene)->head }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Question Type</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ optional($question->questionType)->name }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Order</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->order }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Head</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->head }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Text</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->text }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Explanation</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->explanation }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Points</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->points }}</div>
</div>

<div class="row px-3">
    <div class="col-md-3 bg-light border border-light rounded-left border-right-0 py-1 px-3 mt-1">Answer Count</div> 
    <div class="col-md-9 border border-light rounded-right border-left-0 py-1 px-3 mt-1">{{ $question->answer_count }}</div>
</div>


    </div>
</div>
@endsection
