@extends('layouts.app')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <i class="fa fa-check" aria-hidden="true"></i>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="float-left mx-3 mt-1"><h2>Questions</h2></div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sorteren op
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="?sortby=question_type_id"> @if($sortby==='question_type_id') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Question Type</a>
                    <a class="dropdown-item" href="?sortby=order"> @if($sortby==='order') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Order</a>
                    <a class="dropdown-item" href="?sortby=head"> @if($sortby==='head') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Head</a>

                </div>
            </div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Richting
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="?direction=asc"> @if($direction==='asc') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Asc (A->Z, 0->9)</a>
                    <a class="dropdown-item" href="?direction=desc"> @if($direction==='desc') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Desc (Z->A, 9->0)</a>
                </div>
            </div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aantal
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="?paginate=10"> @if($paginate===10) <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Max 10</a>
                    <a class="dropdown-item" href="?paginate=15"> @if($paginate===15) <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Max 15</a>
                    <a class="dropdown-item" href="?paginate=20"> @if($paginate===20) <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Max 20</a>
                    <a class="dropdown-item" href="?paginate=25"> @if($paginate===25) <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Max 25</a>
                </div>
            </div>
            <div class="float-left ml-3 mt-1">
                <form class="d-inline-block" action="#" method="GET">
                    <div class="input-group">
                        <input name="filter" class="form-control py-2 rounded-left" type="search" value="{{ $filter }}" id="search-input">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary" type="commit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="float-right ml-3 mr-2 mt-2">
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{ route('crest.questions.create') }}" class="btn btn-success" title="Create New Question">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        
        @if(count($questions) == 0)
            <div class="card-body text-center">
                <h4>No Questions Available.</h4>
            </div>
        @else
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                                <th>Question Type</th>
                            <th>Order</th>
                            <th>Head</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($questions as $question)
                        <tr>
                                <td>{{ optional($question->questionType)->name }}</td>
                            <td>{{ $question->order }}</td>
                            <td>{{ $question->head }}</td>

                            <td>

                                <form method="POST" action="{!! route('crest.questions.destroy', $question->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm float-right" role="group">
                                        <a href="{{ route('crest.questions.show', $question->id ) }}" class="btn btn-info" title="Show Question">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('crest.questions.edit', $question->id ) }}" class="btn btn-primary" title="Edit Question">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Question" onclick="return confirm(&quot;Click Ok to delete Question.&quot;)">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card-footer">
            {!! $questions->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection
