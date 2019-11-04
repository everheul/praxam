{{-- question.form (used by question.create and question.edit)
     Edit a question of any type.
     input: $scene, $question
--}}
<div class="form-group row">
    <label for="question_type" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Choose here for answers with:<br /> <b>1.</b> radio buttons.<br /> <b>2.</b> checkboxes.<br /> <b>3.</b> dragable answers in a sortable list.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Question Type
    </label>
    <div class="col-md-5 pl-0">
        <select class="form-control" id="question_type" name="question_type_id">
            @foreach ($question_types as $key => $question_type)
                <option value="{{ $key }}"{{ ($key === optional($question)->question_type_id) ? ' selected' : '' }}>{{ $question_type }}</option>
            @endforeach
        </select>
        {!! $errors->first('question_type_id', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('head') ? 'has-error' : '' }}">
    <label for="head" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Enter a few meaningful words here, as <b>identifier</b> of this question, and as the question's <b>header</b>, when you chose for more questions per scene.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Head
    </label>
    <div class="col-md-5 pl-0">
        <input class="form-control" name="head" type="text" id="head" value="{{ old('head', optional($question)->head) }}" maxlength="191" placeholder="Header">
        {!! $errors->first('head', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('text') ? 'has-error' : '' }}">
    <label for="text" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Question Text
    </label>
    <div class="col-md-9 pl-0">
        <textarea name="text" id="textid" class="ckeditor form-control w-100" placeholder="Question Text" maxlength="5000"
                rows="3" >{!! old('text', optional($question)->text) !!}</textarea>
        {!! $errors->first('text', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('explanation') ? 'has-error' : '' }}">
    <label for="explanation" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Explanation
    </label>
    <div class="col-md-9 pl-0">
         <textarea name="explanation" id="explanation" class="ckeditor form-control w-100" placeholder="Explanation" maxlength="5000"
                rows="3" >{{ old('explanation', optional($question)->explanation) }}</textarea>
        {!! $errors->first('explanation', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('points') ? 'has-error' : '' }}">
    <label for="points" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Points
    </label>
    <div class="col-md-2 pl-0">
        <input class="form-control" name="points" type="number" id="points" value="{{ old('points', optional($question)->points) }}"
               min="1" max="10" placeholder="Question Points">
        {!! $errors->first('points', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>
