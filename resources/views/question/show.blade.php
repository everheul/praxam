{!! App\Helpers\Helper::brToSpace($question->text) !!}
@foreach ($question->answers() as $answer)
    <div class="input-group-prepend mb-1">
        <div class="input-group-text">
            <input type="checkbox" aria-label="">
            <div class="pl-3 py-0">{{ $answer->text }}</div>
        </div>
    </div>
@endforeach
<button class="btn btn-outline-danger px-4 py-1 mt-2">Done</button>
<div class="mt-3 px-3 explanation">
    {!! $question->explanation !!}
</div>
