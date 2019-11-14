{{-- scene.index
     Paginate all scenes
     input: $exam, $scenes, indexargs..
--}}


@extends('layouts.exam')

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
        <div class="card-header headcolor pb-0 pt-2">
            <div class="float-left mx-3 mt-1"><h2>Exam Scenes</h2></div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle appcolor" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Order By
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="?sortby=id"> @if($sortby==='id') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Index</a>
                    <a class="dropdown-item" href="?sortby=head"> @if($sortby==='head') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Head</a>
                    <a class="dropdown-item" href="?sortby=question_count"> @if($sortby==='question_count') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Questions</a>
                    <a class="dropdown-item" href="?sortby=text"> @if($sortby==='text') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Text</a>
                </div>
            </div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle appcolor" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Direction
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="?direction=asc"> @if($direction==='asc') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Asc (A->Z, 0->9)</a>
                    <a class="dropdown-item" href="?direction=desc"> @if($direction==='desc') <i class="fa fa-arrow-right" aria-hidden="true"></i> @endif Desc (Z->A, 9->0)</a>
                </div>
            </div>
            <div class="dropdown float-left ml-3 mt-1">
                <button class="btn btn-outline-dark dropdown-toggle appcolor" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Page Size
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

                        <input name="filter" class="form-control py-2 rounded-left" type="search" placeholder="*" value="{{ str_replace('%','*',$filter) }}" id="search-input">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary appcolor" type="commit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <span class="input-group-append">
                            <a class="btn btn-outline-secondary appcolor" href="?filter=" type="button">
                                <i class="fa fa-close"></i>
                            </a>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        @if(count($scenes) == 0)
        <div class="card-body text-center">
            {{-- todo:  perfect place to explain how we work!
            --}}
            <h4>No Scenes Found</h4>
        </div>
        @else
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-hover table-sm">
                    <thead class="appcolor">
                        <tr>
                            <th></th>
                            <th>Index</th>
                            <th>Head</th>
                            <th>Questions</th>
                            <th>Text</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($scenes as $scene)
                        <tr>
                            <td>
                                <div class="btn-group btn-group-sm float-left" role="group">
                                    <span class="btn btn-info {{ ($scene->is_valid) ? ($scene->is_public) ? 'bg-success' : 'bg-warning' : 'bg-danger' }}"
                                          title="{{ ($scene->is_valid) ? ($scene->is_public) ? 'Published' : 'Not Published' : 'Not Valid' }}">
                                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </td>
                            <td>{{ $scene->id }}</td>
                            <td>{{ $scene->head }}</td>
                            <td>{{ $scene->question_count }}</td>
                            <td>{{ empty($scene->text) ? '' : substr(strip_tags($scene->text),0,70) . '...' }}</td>
                            <td>
                                <form method="POST" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/destroy" accept-charset="UTF-8">
                                    {{ csrf_field() }}
                                    <div class="btn-group btn-group-sm float-right" role="group">
                                        <a href="/exam/{{ $exam->id }}/scene/{{ $scene->id }}/show" class="btn btn-info bg-light ml-a" title="Show Scene">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="/exam/{{ $exam->id }}/scene/{{ $scene->id }}/edit" class="btn btn-primary ml-a" title="Edit Scene">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <button name="delete" type="submit" class="btn btn-danger ml-a" title="Delete Scene" onclick="return confirm(&quot;Click Ok to delete Scene.&quot;)">
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
            {!! $scenes->render() !!}
        </div>
        @endif
        <div class="card-footer appcolor text-center">
            <a class="btn btn-primary" href="/exam/{{ $exam->id }}/scene/create" role="button" id="add_scene">Add Scene</a>
        </div>
    </div>
@endsection
