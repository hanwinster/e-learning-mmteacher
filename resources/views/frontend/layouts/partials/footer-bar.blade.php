<!-- footer section -->
<section id="footer-bar" class="page-section bg-secondary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <h6 class="text-left mb-3">@lang('Our Sponsors')</h6> 
                <p class="footer-sponsor">
                    <img class="sponsor-unesco" src="{{ asset('/assets/img/logos/logo-UNESCO-white.png') }}" alt="UNESCO" title="UNESCO">
                </p>
                <p class="footer-sponsor">
                    <img class="sponsor-formin" src="{{ asset('/assets/img/logos/logo-FORMIN.png') }}" 
                    alt="Ministry for Foreign Affairs of Finland (FORMIN)" title="Ministry for Foreign Affairs of Finland (FORMIN)">
                </p>
            </div>
            <div class="col-md-6 col-lg-8">
                
                <div class="row">
                    <div class="col-6">
                        <h6 class="text-left mb-3 ms-2">@lang('Courses')</h6>
                        @php 
                            $courseCategories = \App\Repositories\CourseCategoryRepository::getAllCourseCategories();
                        @endphp
                        @if(count($courseCategories)) 
                            @foreach($courseCategories as $key => $cc)
                                @if($key < 6 && ($cc->name !== "Assessment" && $cc->name !== "Journalism"))
                                    <a href="{{ route('elearning.browseByCategory') }}/?category={{$cc->id}}"
                                        class="footer-link"  >
                                        @lang($cc->name)
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="col-6">
                        <!-- @if(count($courseCategories)) 
                            @foreach($courseCategories as $key => $cc)
                                @if($key >= 6 && $key < 12)
                                    <a href="{{ route('elearning.browseByCategory') }}/?category={{$cc->id}}"
                                        class="footer-link"  >
                                        @lang($cc->name)
                                    </a>
                                @endif
                            @endforeach
                        @endif -->
                        <h6 class="text-left mb-3 ms-2">@lang('User Manuals')</h6> 
                        <a href="{{ route('user-manuals', [1] ) }}" class="footer-link"  >
                            @lang('Independent Learner User Guide')
                        </a>
                        <h6 class="text-left mt-3 mb-3 ms-2">@lang('Disclaimer')</h6> 
                        <a href="{{ route('terms-privacy' ) }}" class="footer-link"  >
                            @lang('Terms And Conditions')
                        </a>
                        <a href="{{ route('terms-privacy' ) }}/#privacy" class="footer-link"  >
                            @lang('Privacy Policy')
                        </a>
                        <!-- <a href="{{ route('mm-teacher-platform' ) }}" class="footer-link"  >
                            @lang('Landing Page')
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>