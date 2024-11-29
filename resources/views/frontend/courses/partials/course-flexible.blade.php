<div class="left-navi mt-3 mb-3">
    <h6 class="mb-4 ps-2 pe-2">
        {{ strip_tags($course->title) }}
        @php
            $isIntroCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('intro_' . $course->id, $completed);
        @endphp
    </h6>



</div>

<!-- <h4>@lang('Course Lectures')</h4> -->
<div class="left-navi">
    <div class="sidebar text-primary" id="course-left-navi">
        <div class="sidebar-container ps-2 pe-2">
            <div class="sidenav">

                @foreach ($completed as $order)
                    @foreach ($order as $key => $value)
                        @php
                            $title = \App\Repositories\CourseRepository::getTitleFromValue($key, $course);
                            $route = \App\Repositories\CourseRepository::getRouteFromValue($key);
                        @endphp
                        @if (strpos($key, 'lect_') !== false)
                            @php
                                $temp = explode('_', $key);
                                $lecture = \App\Repositories\LectureRepository::findById($temp[1]);
                                //$isAllLectCompleted = \App\Repositories\CourseLearnerRepository::isAllPartsCompleted("lect_", $completed);
                                $isLectCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lect_' . $lecture->id, $completed);
                                $isLectSectionsDefault = $course->lecture_order_type === 'default' ? true : false;
                            @endphp
                            <div class="sidebar-dropdown no-p-l">
                                <div class="sidebar-item collapsed" data-toggle="collapse"
                                    data-target="#collapse-{{ $key }}" aria-expanded="true"
                                    aria-controls="collapse-lecture">
                                    <input type="checkbox" class="me-2 form-check-input"
                                        @if ($isLectCompleted) checked @endif />
                                    <a href="{{ $route }}" style="margin-left: -4px;">
                                        <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                            data-placement="top" title="{{ strip_tags($title) }}">
                                            {{ str_limit(strip_tags($title), 32, '...') }}
                                        </span>
                                    </a>
                                    @if (count($lecture->learningActivities) ||
                                        count($lecture->quizzes) ||
                                        count($lecture->liveSessions) ||
                                        count($lecture->summaries))
                                        <i class="fa fa-angle-right arrow-wrapper ml-auto f-right"></i>
                                    @endif
                                </div>
                                <div class="sidebar-sub-menu collapse" id="collapse-{{ $key }}"> 
                                    @if($isLectSectionsDefault)                                  
                                        @foreach ($lecture->learningActivities as $la)
                                            @php
                                                $islaCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lla_' . $la->id, $completed);
                                                $islaInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr('lla_' . $la->id, $completed);
                                            @endphp
                                            @if ($islaInCompletedArr)
                                                <p class="sub-menu-item">
                                                    <div class="ms-3 form-check form-check-inline">
                                                        
                                                        <input type="checkbox" class="me-2 form-check-input"
                                                            @if ($islaCompleted) checked @endif />
                                                        <a href="{{ route('courses.learning-activity', $la->id) }}" class="">
                                                            <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                                                data-placement="top" title="{{ strip_tags($la->title) }}">
                                                                {{ str_limit(strip_tags($la->title), 28, '...') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </p>
                                            @endif
                                        @endforeach

                                        @foreach ($lecture->quizzes as $quiz)
                                            @php
                                                $islqCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lq_' . $quiz->id, $completed);
                                                $islqInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr('lq_' . $quiz->id, $completed);
                                            @endphp
                                            @if($islqInCompletedArr)
                                                <p class="sub-menu-item">
                                                    <div class="ms-3 form-check form-check-inline">
                                                        
                                                        <input type="checkbox" class="me-2 form-check-input"
                                                            @if ($islqCompleted) checked @endif />
                                                        <a href="{{ route('quiz.show', $quiz->id) }}" class="">
                                                            <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                                                data-placement="top" title="{{ strip_tags($quiz->title) }}">
                                                                {{ str_limit(strip_tags($quiz->title), 28, '...') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </p>
                                            @endif
                                        @endforeach


                                        @foreach ($lecture->liveSessions as $session)
                                            @php
                                                $isLSessCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lsess_' . $session->id, $completed);
                                                $isLSessInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr('lsess_' . $session->id, $completed);
                                            @endphp
                                            @if ($isLSessInCompletedArr)
                                                <p class="sub-menu-item">
                                                    <div class="ms-3 form-check form-check-inline">
                                                        
                                                        <input type="checkbox" class="me-2 form-check-input"
                                                            @if ($isLSessCompleted) checked @endif />
                                                        <a href="{{ route('courses.view-live-session', $session) }}"
                                                            class="">
                                                            <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                                                data-placement="top" title="{{ strip_tags($session->topic) }}">
                                                                {{ str_limit(strip_tags($session->topic), 30, '...') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </p>
                                            @endif
                                        @endforeach

                                        @foreach ($lecture->summaries as $summary)
                                            @php
                                                $isLSumCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lsum_' . $summary->id, $completed);
                                                $isLSumInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr('lsum_' . $summary->id, $completed);
                                            @endphp
                                            @if($isLSumInCompletedArr)
                                                <p class="sub-menu-item">
                                                    <div class="ms-3 form-check form-check-inline">
                                                    
                                                        <input type="checkbox" class="me-2 form-check-input"
                                                            @if ($isLSumCompleted) checked @endif />
                                                        <a href="{{ route('courses.summary', $summary) }}" class="">
                                                            <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                                                data-placement="top" title="{{ strip_tags($summary->title) }}">
                                                                {{ str_limit(strip_tags($summary->title), 30, '...') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </p>
                                            @endif
                                        @endforeach
                                    @else 
                                        @foreach($course->lecture_orders as $idxL => $data)
                                            @foreach($data as $lectureKey => $values)
                                                @foreach($values as $idx2 => $val)
                                                    @php                                         
                                                        $isCurrentSectionCompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted($val, $completed);
                                                        $isCurrentSectionInCompletedArr = \App\Repositories\CourseLearnerRepository::isThisPartInCompletedArr( $val, $completed);
                                                        $currentTitle = \App\Repositories\CourseRepository::getTitleFromValue($val, $course);
                                                        $currentRoute = \App\Repositories\CourseRepository::getRouteFromValue($val);
                                                    @endphp
                                                    @if ($isCurrentSectionInCompletedArr && $lectureKey == $lecture->id)
                                                        <p class="sub-menu-item">
                                                            <div class="ms-3 form-check form-check-inline">                                                           
                                                                <input type="checkbox" class="me-2 form-check-input"
                                                                    @if ($isCurrentSectionCompleted) checked @endif />
                                                                <a href="{{ $currentRoute }}" class="">
                                                                    <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                                                        data-placement="top" title="{{ strip_tags($currentTitle) }}">
                                                                        {{ str_limit(strip_tags($currentTitle), 28, '...') }}
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                       
                                    @endif
                                </div>
                            </div>
                        @elseif(strpos($key, 'assessment_') === false &&
                            strpos($key, 'lq_') === false &&
                            strpos($key, 'lsess_') === false &&
                            strpos($key, 'lla_') === false &&
                            strpos($key, 'lsum_') === false)
                            <div class="sidebar-dropdown no-p-l">
                                <div class="sidebar-item" data-toggle="collapse" data-target="#assess-div"
                                    aria-expanded="true" aria-controls="assess-div">
                                    <input type="checkbox" class="me-2 form-check-input"
                                        @if ($value) checked @endif />
                                    <a href="{{ $route }}" style="margin-left: -4px;">
                                        <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                            data-placement="top" title="{{ strip_tags($title) }}">
                                            {{ str_limit(strip_tags($title), 28, '...') }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach


                <!-- assessment starts -->
                @php
                    $assessmentOnlyFromCompleted = filterFindValueOnly($completed, 'assessment_');
                @endphp
                @if ($course->course_type_id == 1 &&
                    isset($course->assessmentQuestionAnswers) &&
                    count($course->assessmentQuestionAnswers))
                    @if (\App\Repositories\CourseLearnerRepository::isReadyToAssess($completed))
                        @include('frontend.courses.partials.course-lecture-sidebar', [
                            'mainTitle' => 'Course Assessment',
                            'dataTarget' => '#collapse-course-assessment',
                            'submenuId' => 'collapse-course-assessment',
                            'masterData' => $assessmentOnlyFromCompleted,
                            'textToCheckComplete' => 'assessment_',
                            'titleOrTopic' => 'question',
                            'routeText' => 'courses.assessment',
                            'course' => $course,
                        ])
                    @else
                        <div class="sidebar-dropdown no-p-l">
                            <div class="sidebar-item" data-toggle="collapse" data-target="#assess-div"
                                aria-expanded="true" aria-controls="assess-div">
                                <h6 class="mb-2 text-dark form-check form-check-inline">
                                    <input class="form-check-input disabled" type="checkbox" />
                                    <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                        data-placement="right" title="@lang('You can take the assessment after completing the course!')">
                                        <span class="disabled">
                                            @lang('Course Assessment')
                                        </span>
                                    </span>
                                </h6>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- assessment ends -->

                <div class="sidebar-dropdown no-p-l">
                    <div class="sidebar-item" data-toggle="collapse" data-target="#eva-div" aria-expanded="true"
                        aria-controls="eva-div">
                        <h6 class="mb-2 text-dark form-check form-check-inline">
                            @php
                                $isReadyToEvaluate = \App\Repositories\CourseLearnerRepository::isReadyToEvaluate($course);
                                $isEvaDone = \App\Repositories\CourseLearnerRepository::isEvaluationDone($course);
                            @endphp
                            <input class="form-check-input {{ !$isReadyToEvaluate ? 'disabled' : '' }}" type="checkbox"
                                value="option1" @if ($isEvaDone) checked @endif />
                            <!-- <label class="form-check-label"> @lang('Course Evaluations')</label> -->
                            @if ($isReadyToEvaluate)
                                <a href="{{ route('courses.evaluation', $course) }}">
                                    @lang('Course Evaluations')
                                </a>
                            @else
                                <span class="tooltip-info form-check-label" data-toggle="tooltip"
                                    data-placement="right" title="@lang('You can evaluate after completing assessments!')">
                                    <span class="disabled">
                                        @lang('Course Evaluations')
                                    </span>
                                </span>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        @php
            $isReadyToGenerateCerti = \App\Repositories\CourseLearnerRepository::isReadyToGenerateCerti($course);
        @endphp
        @if ($course->course_type_id == 1)
            @if ($isReadyToGenerateCerti)
                <div class="sidebar-container text-left mt-3 ps-2 pe-2">
                    {!! \Form::open(['method' => 'POST', 'route' => 'courses.learner-generate-certificate']) !!}
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <button id="learner-generate-certi" class="btn btn-primary btn-sm p-2 mb-2" type="submit">
                        @lang('Generate Certificate')
                    </button>
                    </form>
                </div>
            @else
                <div class="sidebar-container text-left mt-3  ps-2 pe-2">
                    <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="right"
                        title="@lang('You can generate the certificate after evaluating the course!')">
                        <button id="learner-generate-certi" class="btn btn-primary btn-sm p-2 mb-4 disabled"
                            type="submit">
                            @lang('Generate Certificate')
                        </button>
                    </span>
                </div>
            @endif
        @endif
    </div>
</div>

@section('script')
    @parent
    <script type="text/javascript">
        /*     $("#input-id").rating(); */
        $(document).ready(function() {
            $("#course-left-navi input:checkbox").click(function() {
                return false;
            }); //update-completion          

        });
    </script>
@endsection
