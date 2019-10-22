{{-- exam.edit (admin)
     Open the forms of a scene, its questions and their answers.
--}}
{{-- obsolete --}}

@extends('layouts.exam')

@push('head')
<script src="/js/ckeditor/ckeditor.js"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card w-100">
                <div class="examtext">
                    {{ Form::model($user, array('route' => array('user.update', $user->id)))
                    Form::open(array('url' => '/scene/save')) }}

                    {{ Form::close() }}

                    <form>
                        <div class="form-group scene-info">
                            Scene ~ id: <input class="form-control w-auto d-inline-block"  type="text" id="scene.id"  value="{{ $scene['id'] }}">
                            ~ type: {{ $scene['scene_type_id'] }}
                            @if ($scene['question_count'] > 1)
                                ~ questions: {{ $scene['question_count'] }}
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::select('size', $arr, $def) }}
                            <label for="scene.text">Scene Type</label>
                            <input type="text" class="form-control" id="scene.scene_type_id" value="{{ $scene['scene_type_id'] }}">
                        </div>
                        <div class="form-group">
                            <label for="scene.text">Scene Text</label>
                            <textarea name="scene.text" class="form-control" id="scene.text" rows="3">{{ $scene['text'] }}</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    CKEDITOR.replace( 'scene.text' );
</script>
@endpush

