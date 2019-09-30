
<div class="form-group row {{ $errors->has('scene_id') ? 'has-error' : '' }}">
    <label for="scene_id" class="col-md-2 pt-2 control-label">Scene</label>
    <div class="col-md-10">
        <select class="form-control" id="scene_id" name="scene_id">
        	    <option value="" style="display: none;" {{ old('scene_id', optional($question)->scene_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select scene</option>
        	@foreach ($scenes as $key => $scene)
			    <option value="{{ $key }}" {{ old('scene_id', optional($question)->scene_id) == $key ? 'selected' : '' }}>
			    	{{ $scene }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('scene_id', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('question_type_id') ? 'has-error' : '' }}">
    <label for="question_type_id" class="col-md-2 pt-2 control-label">Question Type</label>
    <div class="col-md-10">
        <select class="form-control" id="question_type_id" name="question_type_id">
        	    <option value="" style="display: none;" {{ old('question_type_id', optional($question)->question_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select question type</option>
        	@foreach ($questionTypes as $key => $questionType)
			    <option value="{{ $key }}" {{ old('question_type_id', optional($question)->question_type_id) == $key ? 'selected' : '' }}>
			    	{{ $questionType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('question_type_id', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('order') ? 'has-error' : '' }}">
    <label for="order" class="col-md-2 pt-2 control-label">Order</label>
    <div class="col-md-10">
        <input class="form-control" name="order" type="text" id="order" value="{{ old('order', optional($question)->order) }}" min="0" max="65535" required="true" placeholder="Enter order here...">
        {!! $errors->first('order', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('head') ? 'has-error' : '' }}">
    <label for="head" class="col-md-2 pt-2 control-label">Head</label>
    <div class="col-md-10">
        <input class="form-control" name="head" type="text" id="head" value="{{ old('head', optional($question)->head) }}" maxlength="191" placeholder="Enter head here...">
        {!! $errors->first('head', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('text') ? 'has-error' : '' }}">
    <label for="text" class="col-md-2 pt-2 control-label">Text</label>
    <div class="col-md-10">
        <input class="form-control" name="text" type="text" id="text" value="{{ old('text', optional($question)->text) }}" maxlength="5000" placeholder="Enter text here...">
        {!! $errors->first('text', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('explanation') ? 'has-error' : '' }}">
    <label for="explanation" class="col-md-2 pt-2 control-label">Explanation</label>
    <div class="col-md-10">
        <input class="form-control" name="explanation" type="text" id="explanation" value="{{ old('explanation', optional($question)->explanation) }}" maxlength="5000" placeholder="Enter explanation here...">
        {!! $errors->first('explanation', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('points') ? 'has-error' : '' }}">
    <label for="points" class="col-md-2 pt-2 control-label">Points</label>
    <div class="col-md-10">
        <input class="form-control" name="points" type="text" id="points" value="{{ old('points', optional($question)->points) }}" min="-32768" max="32767" placeholder="Enter points here...">
        {!! $errors->first('points', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('answer_count') ? 'has-error' : '' }}">
    <label for="answer_count" class="col-md-2 pt-2 control-label">Answer Count</label>
    <div class="col-md-10">
        <input class="form-control" name="answer_count" type="text" id="answer_count" value="{{ old('answer_count', optional($question)->answer_count) }}" min="0" placeholder="Enter answer count here...">
        {!! $errors->first('answer_count', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

