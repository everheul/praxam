@extends('layouts.app')

@section('content')

    <div class="card">
  
        <div class="card-header">

            <h5  class="my-1 float-left">{{ !empty($title) ? $title : 'Question' }}</h5>

            <div class="btn-group btn-group-sm float-right" role="group">

                <a href="{{ route('crest.questions.index') }}" class="btn btn-primary" title="Show All Question">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('crest.questions.create') }}" class="btn btn-success" title="Create New Question">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>

            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('crest.questions.update', $question->id) }}" id="edit_question_form" name="edit_question_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('crest.questions.form', [
                                        'question' => $question,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
