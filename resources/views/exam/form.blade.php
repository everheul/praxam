{{-- exam.form
     input: $exam (may be null)
--}}

<div class="form-group row{{ $errors->has('is_public') ? ' has-error' : '' }}">
    <label for="imageid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left" data-html="true"
                title="Visibility to <b>other users</b>. You can publish your exam as soon as you've made and published at least <b>five scenes</b> for it.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Visability
    </label>
    <div class="col-lg-5">
        <div class="input-group-prepend">
            <div class="input-group-text {{ optional($exam)->is_public ? 'bg-success' : 'bg-warning' }}">
                <input type="hidden" name="is_public" value="0" />
                <input type="checkbox" name="is_public" aria-label="test" value="1"{{ optional($exam)->is_public ? ' checked="1"' : '' }}{{ empty($exam) ? ' disabled="1"' : '' }}/>
                <div class="pl-3 py-0">{{ optional($exam)->is_public ? 'Published' : 'Not Published' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="nameid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="This must be unique, as well as short, for our layout. Try to stay under three words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Exam Title
    </label>
    <div class="col-lg-5">
        <input name="name" type="text" class="form-control" id="nameid" placeholder="Exam Title" value="{{ old('name', optional($exam)->name) }}">
    </div>
    @if(!empty(optional($exam)->image))
        <div class="col-lg-5 pl-0 pr-2 float-right">
            <img src="{{ $exam->image }}" style="height:147px;"  class="img-thumbnail position-absolute" alt="">
        </div>
    @endif
</div>
<div class="form-group row{{ $errors->has('head') ? ' has-error' : '' }}">
    <label for="headid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="The subtitle, to tell just a little bit more about your exam. Still short though, for our layout. Try to stay under six words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Sub Title
    </label>
    <div class="col-lg-5">
        <input name="head" type="text" class="form-control" id="headid" placeholder="Sub Title" value="{{ old('head', optional($exam)->head) }}">
    </div>
</div>
<div class="form-group row{{ $errors->has('newimage') ? ' has-error' : '' }}">
    <label for="imageid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="A nice, suggestive image of ± 500x500 pixels is what you need here. Take your time, it should be good.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Exam Image
    </label>
    <div class="col-lg-5">
        <div class="custom-file">
            <input name="newimage" type="file" class="custom-file-input" id="customFile">
            <label class="custom-file-label text-truncate" for="customFile">{{ old('newimage', empty(optional($exam)->image) ? 'Select Image' : $exam->imageName()) }}</label>
        </div>
    </div>
</div>

<div class="form-group row{{ $errors->has('intro') ? ' has-error' : '' }}">
    <label for="introid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="This could be all one needs to read before taking your exam. A thirty to fifty motivating words should do.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Introduction
    </label>
    <div class="col-lg-10">
        <textarea name="intro" class="form-control" type="text" id="introid" rows="3" placeholder="Introduction">{{ old('intro', optional($exam)->intro) }}</textarea>
    </div>
</div>
<div class="form-group row{{ $errors->has('text') ? ' has-error' : '' }}">
    <label for="textid" class="col col-lg-2 col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Write down everything there is to tell about your exam here. You can use the styles to make it look good.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Description
    </label>
    <div class="col-lg-10">
        <textarea name="text" class="form-control" id="textid" placeholder="Description">{{ old('text', optional($exam)->text) }}</textarea>
    </div>
</div>
