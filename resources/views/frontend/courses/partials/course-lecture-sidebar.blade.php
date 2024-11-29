
{{-- @if($isOnlyOneInSection && !$isOnlyOneInCompletedArr) --}}
<div class="sidebar-dropdown no-p-l">
    <div class="sidebar-item collapsed" data-toggle="collapse" data-target="{{$dataTarget}}" aria-expanded="true" aria-controls="$submenuId">
        <h6 class="mb-2 text-dark form-check form-check-inline">
            @php
                $isCompleted = \App\Repositories\CourseLearnerRepository::isAllPartsCompleted($textToCheckComplete, $completed);
            @endphp
            <input class="form-check-input" type="checkbox" value="option1" @if($isCompleted) checked @endif />
            <label class="form-check-label"> @lang($mainTitle)</label>
        </h6>
        <i class="fa fa-angle-right arrow-wrapper ml-auto f-right"></i>
    </div>
</div>
<div class="sidebar-sub-menu collapse " id="{{$submenuId}}">
    @foreach($masterData as $data)
        @foreach($data as $key => $value)
            @php
                $title = \App\Repositories\CourseRepository::getTitleFromValue($key, $course);
                $route = \App\Repositories\CourseRepository::getRouteFromValue($key);
                $lectureId = strpos($key, 'lect_') !== false ? explode("_",$key)[0] : null; 
                $id = \App\Repositories\CourseRepository::getIdFromValue($key, $course);
                $isEachCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted($textToCheckComplete.$id, $completed);
                $isEachInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr($textToCheckComplete.$id, $completed);
            @endphp
            @if($lectureId == null && $isEachInCompletedArr)
                <div class="quiz-wrapper">
                    <div class="ms-3 form-check form-check-inline">                   
                        <input type="checkbox" class="me-2 form-check-input" @if($isEachCompleted) checked @endif />
                        <a href="{{ $route }}">
                            
                            <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="top" title="{{ strip_tags($title) }}">
                                @if($titleOrTopic == 'title')
                                    {{ str_limit(strip_tags($title), 30, '...') }}
                                @else
                                    {{ str_limit(strip_tags($title), 30, '...') }}
                                @endif
                            </span>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
</div>