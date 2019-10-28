{{-- scene.type1.edit
     Edit a scene of type 1, its question and answers.
     input: $scene (with: exam, questions, answers), $sidebar

                            <a class="btn btn-danger" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/destroy" role="button" onclick="return confirm(&quot;Click OK to delete this Scene. Warning: this cannot be undone!&quot;)">Delete</a>
     todo: add/delete/edit question options
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>{{ $pagehead }}</h3>
                <hr />
                <form method="post" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $scene->exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $scene->id }}">

                    {{-- HEAD --}}
                    <div class="form-group row">
                        <label for="headid" class="col-lg-3 col-xl-2 col-form-label">Scene Head *<p>± four or five words</p></label>
                        <div class="col-lg-9 col-xl-10">
                            <input name="head" value="{{ old('head', $scene->head) }}" type="text" class="form-control" id="head" placeholder="Scene Head">
                        </div>
                        {{--
                        <div class="col-sm-5 form-text">
                            <span class="text-secondary">The <b>Scene Head</b> stands on top of your scene, and is visible in your scenes-list.
                                Try to keep it as short, meaningful and unique as possible.
                                {!! $errors->first('head', '<span class="text-danger">:message</span>') !!}</span>
                        </div>
                        --}}
                    </div>

                    {{-- TYPE --}}
                    <div class="form-group row {{ $errors->has('scene_type_id') ? 'has-error' : '' }}">
                        <label for="scene_type_id" class="col-lg-3 col-xl-2 pt-2 control-label">Scene Type<p>± save when changed</p></label>
                        <div class="col-lg-9 col-xl-10">
                            <select name="scene_type_id" class="form-control" id="scene_type_id">
                                @foreach ($scene_types as $key => $scene_type)
                                    <option value="{{ $key }}"{{ $key === $scene->scene_type_id ? ' selected' : '' }}>{{ $scene_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--
                        <div class="col-sm-5 text-secondary">
                            A <b>Single Question Scene</b> contains just one question, of any kind.
                            <b>Multi Question Scenes</b> can have more questions about one story - which can include instructions and a picture of its own.
                        </div>
                        --}}
                    </div>
                    {{-- CONTROL --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-xl-2 control-label" for="submit"></label>
                        <div class="col-sm-9 col-xl-10">
                            <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $scene->exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
                <hr />

                {{-- QUESTIONS --}}
                @foreach($scene->questions as $question)
                    <div class="form-group row">
                        <label class="col-lg-3 col-xl-2 col-form-label">Question {{ $question->order }}</label>
                        <div class="col-lg-9 col-xl-10">
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
                    <label class="col-lg-3 col-xl-2 col-form-label"></label>
                    <div class="col-lg-9 col-xl-10">
                        <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')
