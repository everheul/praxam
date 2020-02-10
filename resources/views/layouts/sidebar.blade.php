{{-- layouts.sidebar
    uses: $sidebar
--}}
<div id="sidebar" class="clearfix">
    <div class="sidebar-arrow"></div>
    @foreach ($sidebar as $block)
        @switch($block['type'])
            @case('sbar-hr')
                <hr />
                @break
            @case('sbar-head')
                <div class="sbarhead py-1 mx-3 mt-2">
                    <div class="sbar_head">{!! $block['head'] !!}</div>
                    <div class="sbar_text">{{ $block['text'] }}</div>
                </div>
                @break
            @case('sbar-block')
                <div class="sbarblock py-1 mx-3 mt-2">
                    <div class="sbar_head">{!! $block['head'] !!}</div>
                    <div class="sbar_text">{{ $block['text'] }}</div>
                </div>
                @break
            @case('sbar-link')
                <a class="sbarlink btn btn-outline-{{ $block['color'] }} d-block py-2 mx-3 mt-2" href="{{ $block['href'] }}">
                    <div class="sbar_head">{{ $block['head'] }}</div>
                    @if(!empty($block['text']))
                        <div class="sbar_text">{{ $block['text'] }}</div>
                    @endif
                </a>
                @break
            @case('sbar-button')
                <a href="{{ $block['href'] }}" class="btn btn-outline-{{ $block['color'] }} py-2 mx-3 mt-2 d-block">{{ $block['head'] }}</a>
                @break
            @case('sbar-scene')
                <a href="{{ $block['href'] }}" class="btn btn-sm btn-{{ $block['color'] }} py-2 mx-3 mt-2 d-block">{{ $block['head'] }}</a>
                @break
            @case('sbar-delete')
                <div class="d-block mx-3 mt-2">
                    <form method="POST" action="{{ $block['href'] }}" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <button name="delete" type="submit" class="btn w-100 btn-outline-danger" {!! $block['msg'] !!}>{{ $block['head'] }}</button>
                    </form>
                </div>
                @break
        @endswitch
    @endforeach
</div>

@push('ready')
    // activate sidebar click
    $('.sidebar-arrow').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
    });
@endpush
