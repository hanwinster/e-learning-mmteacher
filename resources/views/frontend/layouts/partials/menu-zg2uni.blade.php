@if($isZawgyi) 
    <span class="force-uni-for-zg">
        {!! Rabbit::zg2uni($text) !!}
    </span>
@else
    {!! $text !!}
@endif