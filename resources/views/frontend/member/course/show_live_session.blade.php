<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">
        @if(!\App\Repositories\CoursePermissionRepository::canAdd($course)) 
                <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                        title="@lang('Cannot create new as the course already had course takers')">
                        <a class="btn btn-secondary disabled text-white pull-right">
                    {{__('New')}}
                </a></span>
            @else
                <a href="{{route('member.course.live-session.create', $course->id)}}"  
                    class="btn btn-primary btn-md">@lang('New')
                </a>
            @endif
        </h4>
    </div>
    
    <div class="card-body table-responsive">
        @if( count($sessions_for_only_course) >0 || count($sessions) > 0)
            <table class="table table-bordered no-footer table-top-border">
                <tbody>
                    <tr>
                        <td>
                            <table class="table table-bordered no-footer quiz_table">
                                <thead>
                                    @include('frontend.member.live-session.session_header', ['heading' => strip_tags($course->title) ])
                                </thead>
                                <tbody>
                                    @include('frontend.member.live-session.session_row', ['sessions' => $sessions_for_only_course, 'lectureId' => null ])
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @php
                        $courseLectures = \App\Models\Lecture::where('course_id', $course->id)->get();                    
                    @endphp

                    @foreach($courseLectures as $key=> $lect)                  
                        <tr>
                            <td>
                                <table class="table table-bordered no-footer quiz_table">
                                    <thead>
                                        @include('frontend.member.live-session.session_header', ['heading' => strip_tags($lect->lecture_title)])
                                    </thead>
                                    <tbody>                                        
                                        @include('frontend.member.live-session.session_row', ['sessions' => $sessions, 'lectureId' => $lect->id ] )                                    
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="info-text">@lang('No Live Session For This Course Yet. Please create one by clicking New button!')</p>                                
        @endif
    </div>
</div>
