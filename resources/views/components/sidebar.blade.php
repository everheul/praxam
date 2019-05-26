{{-- components / sidebar
    uses: $sidebar
--}}
<div id="sidebar">
    <div class="sidebar-arrow">
    </div>
    @foreach ($sidebar as $block)
        @switch($block['type'])
            @case('sbar-head')
                <div class="sbarhead py-1 mx-3 mt-2">
                    <div class="sbar_head">{{ $block['head'] }}</div>
                    <div class="sbar_text">{{ $block['text'] }}</div>
                </div>
                @break
            @case('sbar-block')
                <div class="sbarblock py-1 mx-3 mt-2">
                    <div class="sbar_head">{{ $block['head'] }}</div>
                    <div class="sbar_text">{{ $block['text'] }}</div>
                </div>
                @break
            @case('sbar-link')
                <a class="sbarlink btn btn-outline-dark d-block py-1 mx-3 mt-2" href="{{ $block['href'] }}">
                    <div class="sbar_head">{{ $block['head'] }}</div>
                    <div class="sbar_text">{{ $block['text'] }}</div>
                </a>
                @break
            @case('sbar-button')
                <a href="{{ $block['href'] }}" class="btn btn-outline-{{ $block['color'] }} py-2 mx-3 mt-2 d-block">{{ $block['head'] }}</a>
                @break
            @case('sbar-scene')
                <a href="{{ $block['href'] }}" class="btn btn-sm btn-{{ $block['color'] }} py-2 mx-3 mt-2 d-block">{{ $block['head'] }}</a>
                @break
        @endswitch
    @endforeach
</div>
