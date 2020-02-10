{{-- question.form (used by question.create and question.edit)
     Edit a question of any type.
     input: $scene, $question
--}}

<div class="form-group row">
    <label for="head" class="col-lg-2 pt-2 col-form-label @error('head')text-danger @enderror">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Enter a few meaningful words here, as <b>identifier</b> of this question, and as the question's <b>header</b>, when you chose for more questions per scene.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Head
    </label>
    <div class="col-md-5">
        <input class="form-control" name="head" type="text" id="head" value="{{ old('head', optional($question)->head) }}" maxlength="191" placeholder="Header">
        @error('head') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="question_type" class="col-md-2 pt-2 col-form-label @error('question_type_id')text-danger @enderror">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Choose here for answers with:<br /> <b>1.</b> radio buttons.<br /> <b>2.</b> checkboxes.<br /> <b>3.</b> draggable answers in a sortable list.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Question Type
    </label>
    <div class="col-lg-5">
        <select class="form-control" id="question_type" name="question_type_id">
            @foreach ($question_types as $key => $question_type)
                <option value="{{ $key }}"{{ ($key === optional($question)->question_type_id) ? ' selected' : '' }}>{{ $question_type }}</option>
            @endforeach
        </select>
        @error('question_type_id') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="text" class="col-md-2 pt-2 col-form-label @error('text')text-danger @enderror">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Question Text
    </label>
    <div class="col-lg-10">
        <textarea name="text" id="textid" class="ckeditor form-control w-100" placeholder="Question Text" maxlength="5000"
                rows="3" >{!! old('text', optional($question)->text) !!}</textarea>
        @error('text') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="explanation" class="col-md-2 pt-2 col-form-label @error('explanation')text-danger @enderror">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Explanation
    </label>
    <div class="col-lg-10">
         <textarea name="explanation" id="explanation" class="ckeditor form-control w-100" placeholder="Explanation" maxlength="5000"
                rows="3" >{{ old('explanation', optional($question)->explanation) }}</textarea>
        @error('explanation') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="points" class="col-md-2 pt-2 col-form-label @error('points')text-danger @enderror">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Points
    </label>
    <div class="col-lg-2">
        <input class="form-control" name="points" type="number" id="points" value="{{ old('points', optional($question)->points) }}"
               min="1" max="10" placeholder="Question Points">
        @error('points') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>
