{{-- exam.form
     input: $exam (may be null)
--}}
<div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
    <label for="nameid" class="col col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="This must be unique, as well as short, for our layout. Try to stay under three words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Exam Title
    </label>
    <div class="col-sm-10 pl-0">
        <input name="name" type="text" class="form-control" id="nameid" placeholder="Exam Title" value="{{ old('name', optional($exam)->name) }}">
    </div>
</div>
<div class="form-group row{{ $errors->has('head') ? ' has-error' : '' }}">
    <label for="headid" class="col col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="The subtitle, to tell just a little bit more about your exam. Still short though, for our layout. Try to stay under six words.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Sub Title
    </label>
    <div class="col-sm-10 pl-0">
        <input name="head" type="text" class="form-control" id="headid" placeholder="Sub Title" value="{{ old('head', optional($exam)->head) }}">
    </div>
</div>
<div class="form-group row{{ $errors->has('intro') ? ' has-error' : '' }}">
    <label for="introid" class="col col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="This could be all one needs to read before taking your exam. A thirty to fifty motivating words should do.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Introduction
    </label>
    <div class="col-sm-10 pl-0">
        <textarea name="intro" class="form-control" type="text" id="introid" rows="3" placeholder="Introduction">{{ old('intro', optional($exam)->intro) }}</textarea>
    </div>
</div>
<div class="form-group row{{ $errors->has('newimage') ? ' has-error' : '' }}">
    <label for="imageid" class="col col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="A nice, suggestive image of ± 500x500 pixels is what you need here. Take your time, it should be good.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Exam Image
    </label>
    <div class="col-sm-10 pl-0">
        <div class="custom-file">
            <input name="newimage" type="file" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">{{ old('newimage', empty($exam) ? 'Select Image' : $exam->imageName()) }}</label>
        </div>
        {{-- obsolete? does custom-file work?
        <input type="text" class="form-control" id="show_image" placeholder="Select Image" disabled>
        <input name="newimage" type="file" id="upload_image" value="{{ old('newimage', optional($exam)->imageName()) }}">
        --}}
    </div>
</div>
<div class="form-group row{{ $errors->has('text') ? ' has-error' : '' }}">
    <label for="textid" class="col col-form-label">
        <button type="button" class="btn btn-sm btn-info btooltip" data-toggle="tooltip" data-placement="left"  data-html="true"
                title="Write down everything there is to tell about your exam here. You can use the styles to make it look good.">
            <i class="fa fa-info" aria-hidden="true"></i>
        </button>
        Description
    </label>
    <div class="col-sm-10 pl-0">
        <textarea name="text" class="form-control" id="textid" placeholder="Description">{{ old('text', optional($exam)->text) }}</textarea>
    </div>
</div>
