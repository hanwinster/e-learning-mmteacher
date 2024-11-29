<?php
    $isMm = App::getLocale() == 'my-MM' ? true : false;
?>
<!-- About Section-->
<section class="page-section bg-primary text-white mb-0 vh-100" id="about">
    <div class="container">
        <!-- About Section Heading-->
        <h2 class="page-section-heading text-center text-white mb-3">
           
                @if($isMm)
                    {{ $about->title_mm }}
                @else
                    {{ $about->title }}
                @endif
        </h2>
        <!-- <divider color="text-white" bg-color="bg-white"></divider> -->
        <!-- About Section Content-->
        <div class="row">
            <!-- <div class="col-lg-4 ms-auto">
                <p class="lead">E-learning is developed for everyone who are interested in learning.</p>
            </div> -->
          
            <div class="col-12">
                <p class="lead">
                    
                    @if($isMm)
                        {!! Blade::compileString($about->body_mm) !!} 
                    @else
                        {!! Blade::compileString($about->body) !!} 
                    @endif
                </p>
            </div>
            
        </div>
    </div>
</section>

<!-- FAQ Section-->
<section class="page-section mb-3" id="faq">
    <div class="container">
        <!-- faq Section Heading-->
        <h2 class="page-section-heading text-center mb-5">@include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => null, 'text' => __('Frequently Asked Questions') ])</h2>
        <!-- <divider></divider> -->
        <!-- faq Section Content-->
        <!-- <div class="tab">
            @foreach ($categories as $category)
                <button class="tablinks" onclick="openVerticalTab(event, '{{ $category->title }}')">{{ $category->title }}</button>
            @endforeach
        </div>
        @foreach ($categories as $category)
            <div id="{{ $category->title }}" class="tabcontent">
                <h3>{{ $category->title }}</h3>
                <p>{{ $category->title }} - contents</p>
            </div>
        @endforeach -->
        <div class="col-12 mx-auto">
            <div class="accordion" id="accordionEx">
                @foreach($faqs as $key => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{$faq->id}}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$faq->id}}" 
                                aria-expanded="false" aria-controls="collapse-{{$faq->id}}"> <!-- {{ $key ==0 ? true : false }} -->
                                @php
                                    if($isMm) {
                                        echo htmlspecialchars_decode(
                                            htmlentities(
                                                strip_tags($faq->question_mm, '<a>'),
                                                ENT_NOQUOTES, 'UTF-8', false)
                                            , ENT_NOQUOTES
                                        );
                                    } else {
                                        echo htmlspecialchars_decode(
                                            htmlentities(
                                                strip_tags($faq->question, '<a>'),
                                                ENT_NOQUOTES, 'UTF-8', false)
                                            , ENT_NOQUOTES
                                        );
                                    }
                                    
								@endphp
                            </button>
                        </h2>
                        <div id="collapse-{{$faq->id}}" class="accordion-collapse collapse" 
                            aria-labelledby="heading-{{$faq->id}}" data-bs-parent="#accordionEx"> <!-- {{ $key ==0 ? 'collapse show' : 'collapse' }} -->
                            <div class="accordion-body pt-2 pb-2">
                                <!-- @php
									echo htmlspecialchars_decode(
										htmlentities(
											strip_tags($faq->answer, '<a>'),
											ENT_NOQUOTES, 'UTF-8', false)
										, ENT_NOQUOTES
									);
								@endphp -->
                                @if($isMm) 
                                    {!! $faq->answer_mm !!}
                                @else
                                    {!! $faq->answer !!}
                                @endif
                            </div> 
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- <div class="col-12"> 
                <iframe width="100%" height="300" 
                    src="https://pwa.latlatweb.com/storage/8039/Unsung-Hero-(Official-HD)--TVC-Thai-Life-Insurance.mp4" 
                            title="YouTube video player" frameborder="0" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ></iframe> 
            </div> -->
    </div>
</section>

