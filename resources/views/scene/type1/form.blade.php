{{-- scene.type1.form
     Edit a scene of type 1, only the header, that is.
     input: $scene (with: exam, questions, answers)
--}}
<div class="form-group row">
    <label for="headid" class="col-sm-2 col-form-label">Scene Head</label>
    <div class="col-sm-10">
        <input name="scene[head]" type="text" class="form-control" id="headid" placeholder="Scene Head" value="{{ $scene->head }}">
    </div>
</div>
