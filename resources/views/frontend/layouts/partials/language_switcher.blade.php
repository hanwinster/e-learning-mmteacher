<form class="mb-3 mb-lg-0 me-lg-3">
    <div class="dropdown">
        <span class="dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                @if (App::isLocale($localeCode))
                @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' =>$properties['native'] ])
                @endif
            @endforeach
        </span>
        <!-- <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" 
        data-bs-toggle="dropdown" aria-expanded="false">
                        {{__('All') }}
                    </button> -->
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1"> 
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li>
                <a class="dropdown-item" rel="alternate" 
                    hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => $properties['native'] ])
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</form>