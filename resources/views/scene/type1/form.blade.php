{{-- scene.type1.form
     Edit a scene of type 1, only the header, that is.
     input: optional($scene), $scene_types
--}}

<div class="form-group row{{ $errors->has('head') ? ' has-error' : '' }}">
    <label for="headid" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Main identifier and header of this scene and question(s), so preferably meaningful and unique, and short enough to fit our layout. Try to stay under six words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Head
    </label>
    <div class="col-md-5 pl-0">
        <input name="head" type="text" class="form-control" id="head" placeholder="Scene Header"  value="{{ old('head', optional($scene)->head) }}">
        {!! $errors->first('head', '<p class="form-text text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row{{ $errors->has('scene_type_id') ? ' has-error' : '' }}">
    <label for="scene_type_id" class="col-md-2 pt-2 control-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Choose here for a scene with:<br /> <b>A.</b> No story, one question of any kind.<br /> <b>B.</b> One story, more questions, instructions and a picture.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Scene Type
    </label>
    <div class="col-md-5 pl-0">
        <select class="form-control" id="scene_type_id" name="scene_type_id">
            @foreach ($scene_types as $key => $scene_type)
                <option value="{{ $key }}"{{ $key === optional($scene)->scene_type_id ? ' selected' : '' }}>{{ $scene_type }}</option>
            @endforeach
        </select>
    </div>
</div>
