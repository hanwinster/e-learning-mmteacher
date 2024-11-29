{{ Form::open(array('route' => 'article.index', 'method' => 'get')) }}
<div class="form-group input-group">
    <input type="text" class="form-control" placeholder="Search Media..." name="search" id="search" value="{{ request('search') }}">
    <div class="input-group-append">
        <button class="btn btn-primary" type="submit">{{ __('Go!') }}</button>
    </div>
</div>
</form>
