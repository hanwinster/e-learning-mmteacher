<div class="rating ml-4">
    @for($i= 1; $i <= 5; $i++)
    <label class="fa fa-star {{ $i <= $rating ? '': 'empty' }}"></label>
    @endfor
</div>
