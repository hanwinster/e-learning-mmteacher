<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">
            @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                        title="@lang('Cannot create new as the course owner locked it')">
                        <a class="btn btn-secondary disabled text-white pull-right">
                    {{__('New')}}
                </a></span>
            @else 
                <a href="{{route('member.quiz.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a> 
            @endif
        </h4>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered no-footer table-top-border">
            <tbody>
                <tr>
                    <td>
                        <table class="table table-bordered no-footer quiz_table">
                            <thead>
                                @include('frontend.member.quiz.quiz_header', ['heading' => strip_tags($course->title) ])
                            </thead>
                            <tbody>
                                @include('frontend.member.quiz.quiz_row', ['quizzes' => $quizs_for_only_course])
                            </tbody>
                        </table>
                    </td>
                </tr>
                @php
                    $lectures = \App\Models\Lecture::where('course_id', $course->id)->get();
                @endphp

                @foreach($lectures as $lecture)
                    <tr>
                        <td>
                            <table class="table table-bordered no-footer quiz_table">
                                <thead>
                                    @include('frontend.member.quiz.quiz_header', ['heading' => strip_tags($lecture->lecture_title) ])
                                </thead>
                                <tbody>
                                    
                                    @include('frontend.member.quiz.quiz_row', ['quizzes' => $lecture->quizzes])
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>