<div class="form-group row{{ $errors->has('text') ? 'has-error' : '' }}">
    <label for="textid" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Story
    </label>
    <div class="col pl-0">
        <textarea name="text" id="textid" class="ckeditor w-100" style="height: 224px;" placeholder="Scene Text" rows="9" >{!! old('text', optional($scene)->text) !!}</textarea>
        {!! $errors->first('text', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row{{ $errors->has('instructions') ? 'has-error' : '' }}">
    <label for="instid" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Instructions
    </label>
    <div class="col pl-0">
        <textarea name="instructions" id="instid" class="form-control w-100" placeholder="Scene Instructions" rows="3" >{{ old('instructions', optional($scene)->instructions) }}</textarea>
        {!! $errors->first('instructions', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>

<div class="form-group row{{ $errors->has('newimage') ? 'has-error' : '' }}">
    <label for="show_image" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Image
    </label>
    <div class="col-md-10 pl-0">
        <div class="custom-file">
            <input name="newimage" type="file" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">{{ old('newimage', empty(optional($scene)->image) ? 'Select Image' : $scene->imageName()) }}</label>
        </div>
        {!! $errors->first('newimage', '<p class="form-text text-muted">:message</p>') !!}
    </div>
</div>
