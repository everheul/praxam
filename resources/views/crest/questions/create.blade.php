@extends('layouts.app')

@section('content')

    <div class="card">

        <div class="card-header">
            
            <h5  class="my-1 float-left">Create New Question</h5>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('crest.questions.index') }}" class="btn btn-primary" title="Show All Question">
                    <i class="fa fa-list" aria-hidden="true"></i>
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

            <form method="POST" action="{{ route('crest.questions.store') }}" accept-charset="UTF-8" id="create_question_form" name="create_question_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('crest.questions.form', [
                                        'question' => null,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
