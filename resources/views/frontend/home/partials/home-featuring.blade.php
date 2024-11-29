<?php
    $isZawgyi = App::getLocale() == 'my-ZG' ? true : false;
?>
<!-- Courses Section-->
<section class="page-section Courses" id="courses">
    <div class="container">
        <!-- Courses Section Heading-->
        <h2 class="page-section-heading text-center text-secondary mb-5">@include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Courses') ])</h2>
        <!-- <divider></divider> -->
        <!-- Courses Grid Items-->
        <div id="course-slider" class="row justify-content-center">
            <!-- Courses Item 1-->
            @if (isset($courses)) 
                @foreach ($courses as $course)
                    <div class="col-12 col-md-6 col-xl-4 mb-2">
                        <div class="portfolio-item mx-auto {{ $course->lang == 'my-MM' ? 'my-MM' : '' }}">
                            <div class="card">
                            @if($course->getThumbnailPath())
                                <img class="card-img-top" src="{{ asset($course->getThumbnailPath()) }}" alt="{{ $course->title }}">
                            @elseif(!file_exists(public_path($course->getThumbnailPath())))
                                @foreach($course->media as $mediafile)
                                    @php
                                        $_filename = $mediafile->file_name;
                                    @endphp
                                    <img class="card-img-top" src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" 
                                            alt="{{ strip_tags($course->title) }}">
                                @break
                                @endforeach                            
                            @else
                                <img class="card-img-top" src="{{ asset('assets/img/vector/3.png') }}" 
                                      alt="{{ strip_tags($course->title) }}">
                            @endif                            
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                                title="{{ strip_tags($course->title) }}">
                                            {{ str_limit(strip_tags($course->title), 28, '...') }}
                                        </span>
                                    </h4>
                                    <p class="card-text">
                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                                title="{{ strip_tags($course->description) }}">
                                            {{ str_limit(strip_tags($course->description), 88, '...') }}
                                        </span>
                                    </p>
                                    <a href="{{ url('/e-learning/courses') }}/{{ $course->slug }}" class="btn btn-primary btn-md">@include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Learn More') ])</a>                               
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach 
            @endif           
        </div>
        <div class="row justify-content-center mt-3">
             <div class="text-center">
                <a class="btn btn-lg btn-primary" href="{{ route('courses.index') }}">                  
                @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('View All') ])&nbsp;<!--i class="fas fa-greater-than"></i-->
                </a>
            </div>
        </div>
    </div>
</section>

