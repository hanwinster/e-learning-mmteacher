
    <div class="container text-center">

        <h2>{{__('Ready to get started?') }}</h2>

        <div class="row mt-3">
        {{ Form::open(array('route' => 'elibrary.index', 'method' => 'get', 'class' => 'col-md-8 col-xl-5 input-glass mx-auto')) }}
            <div class="input-group input-group-lg1">
                <div class="input-group-prepend">
                    <span class="input-group-text"><strong class="fa fa-search"></strong></span>
                </div>
                <input title="Search" type="text" aria-label="Search" name="search" class="form-control" placeholder="Enter your keyword" value="{{ request('search') }}">
                <span class="input-group-append">
                    <button class="btn">{{ __('Search') }}</button>
                </span>
            </div>
            <div class="mt-3 text-right">
                <a href="{{ route('search.advanced') }}" class="text-blue">{{ __('Advanced Search') }}</a>
            </div>
        </form>
        </div>

    </div>
