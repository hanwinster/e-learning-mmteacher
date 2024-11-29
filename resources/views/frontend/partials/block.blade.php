@php
$blocks = App\Repositories\BlockRepository::getVisibleBlocks($region);
@endphp

@if (isset($blocks))
    @foreach($blocks as $block)
        @if($block->region == $region)
        <div class="block">
            @if ($block->hide_title != 1)
            <div class="block-header">
                <h4>{{ $block->title }}</h4>
            </div>
            @endif
            <div class="block-body">
                {!! Blade::compileString($block->body) !!}
            </div>
        </div>
        @endif
    @endforeach
@endif