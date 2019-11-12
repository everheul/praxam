{{-- scene.type2.form (used by scene.type2.edit)
     input: optional($scene)
--}}

<div class="form-group row">
    <label for="imageid" class="col-lg-2 pt-2 col-form-label @error('is_public')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Visibility to other users. You can publish your scene when you've made enough questions for it, depending on the type.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Visability
    </label>
    <div class="col-lg-5">
        <div class="input-group-prepend">
            <div class="input-group-text {{ optional($scene)->is_public ? 'bg-success' : 'bg-warning' }}">
                <input type="hidden" name="is_public" value="0" />
                <input type="checkbox" name="is_public" aria-label="test" value="1"{{ optional($scene)->is_public ? ' checked="1"' : '' }}{{ empty($scene) ? ' disabled="1"' : '' }}/>
                <div class="pl-3 py-0">{{ optional($scene)->is_public ? 'Published' : 'Not Published' }}</div>
            </div>
        </div>
        @error('is_public') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="headid" class="col-lg-2 pt-2 col-form-label @error('head')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Main identifier and header of this scene and question(s), so preferably meaningful and unique, and short enough to fit our layout. Try to stay under six words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Head
    </label>
    <div class="col-lg-5">
        <input name="head" type="text" class="form-control" id="head" placeholder="Scene Header"  value="{{ old('head', optional($scene)->head) }}">
        @error('head') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="scene_type_id" class="col-lg-2 pt-2 col-form-label @error('scene_type_id')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Choose here for a scene with:<br /> <b>A.</b> No story, one question of any kind.<br /> <b>B.</b> One story, more questions, instructions and a picture.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Scene Type
    </label>
    <div class="col-lg-5">
        <select class="form-control" id="scene_type_id" name="scene_type_id">
            @foreach ($scene_types as $key => $scene_type)
                <option value="{{ $key }}"{{ $key === optional($scene)->scene_type_id ? ' selected' : '' }}>{{ $scene_type }}</option>
            @endforeach
        </select>
        @error('scene_type_id') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="show_image" class="col-lg-2 col-form-label @error('newimage')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Image
    </label>
    <div class="col-lg-5">
        <div class="custom-file">
            <input name="newimage" type="file" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">{{ old('newimage', empty(optional($scene)->image) ? 'Select Image' : $scene->imageName()) }}</label>
        </div>
        @error('newimage') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="textid" class="col-lg-2 col-form-label @error('text')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Story
    </label>
    <div class="col-lg-10">
        <textarea name="text" id="textid" class="ckeditor w-100" style="height: 224px;" placeholder="Scene Text" rows="9" >{!! old('text', optional($scene)->text) !!}</textarea>
        @error('text') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group row">
    <label for="instid" class="col-lg-2 col-form-label @error('instructions')text-danger @enderror ">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="todo">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Instructions
    </label>
    <div class="col-lg-10">
        <textarea name="instructions" id="instid" class="form-control w-100" placeholder="Scene Instructions" rows="3" >{{ old('instructions', optional($scene)->instructions) }}</textarea>
        @error('instructions') <div class="form-text text-danger m-0">{{ $message }}</div> @enderror
    </div>
</div>
