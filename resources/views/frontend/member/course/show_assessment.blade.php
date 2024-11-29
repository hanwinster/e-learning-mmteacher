<div class="row">
    <div class="col-12 mt-4">
        @if($course->course_type_id ==1)
            <h5 class="text-dark">
                {{__('Assessment Setting')}}
            </h5>
            <table class="table no-footer">           
                <tr>
                    <td>@lang('The items which will affect the certification')</td>
                    <td>@if($course->item_affect_certification == 1)
                            @lang('Assessment Score')
                        @else
                            @lang('Completion Only')
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>@lang('Acceptable Score For Assessment')</td>
                    <td>
                        @if(($course->acceptable_score_for_assessment))
                            {{ $course->acceptable_score_for_assessment }} 
                        @else 
                            65
                        @endif
                    </td>
                </tr>
            </table>
            <div class="px-3">
                @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                        title="@lang('Cannot edit or delete as the course already had course takers')">
                        <a class="btn pr-2 pl-2 btn-outline disabled">
                            <i class="fas fa-edit"></i> {{ __('Edit') }} 
                        </a>
                    </span>
                @else
                    @if($canEdit) 
                        <a href="{{route('member.course.assessment.edit', $course->id)}}" class="btn btn-primary btn-sm">@lang('Edit')</a>
                    @endif
                @endif
            </div>
        @else
            {{ __('You have chosen non-certified type for this course!')}}
        @endif
    </div>
</div>

@if($course->course_type_id ==1)
    <div class="row">
        <div class="col-12 mt-4">
            <h5 class="text-dark">
                {{__('Assessment Questions & Answers')}}&nbsp;
                @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                            title="@lang('Cannot create new as the course owner locked it')">
                            <a class="btn btn-secondary disabled text-white pull-right">
                        {{__('New Question & Answers')}}
                    </a></span>
                @else 
                    
                        <a href="{{route('member.course.assessment-qa.create', $course->id)}}" class="btn btn-primary btn-sm"
                            >@lang('New Question & Answers')
                        </a>
                @endif
                   
            </h5>
            <div class="table-responsive">
                <table class="table no-footer"> 
                    <thead>          
                        <tr>
                            <th colspan="6">@lang('Questions and Answers')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="60">@lang('No#')</th>
                            <th>{{__('Questions')}}</th>
                            <th>{{__('Question Type')}}</th>
                            <!-- <th>{{__('Order') }}</th> -->
                            <th>{{__('Created At')}}</th>
                            <th width="150" class="text-center">{{__('Actions') }}</th>
                        </tr>
                        
                        @if(count($assessmentQAs))
                            @foreach($assessmentQAs as $idx => $assessmentQA)
                                <tr>
                                    <td>{{$idx+1}}</td>
                                    <td>
                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                            title="{{strip_tags($assessmentQA->question)}}">
                                            {{ str_limit(strip_tags($assessmentQA->question), 60, '...') }}
                                        </span>
                                    </td>
                                    <!-- <td>
                                        @php 
                                            $alphabets = ['A','B','C']; //,'D','E','F','G','H','I','J'];                                        
                                        @endphp
                                        @foreach($assessmentQA->answers as $index => $ans)
                                            @if($index < 3)
                                                @if($index < 2 )
                                                    {{$alphabets[$index] }}.&nbsp;{{ strip_tags($ans) }} <br>
                                                @else
                                                    ...
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($assessmentQA->right_answers as $right)                                      
                                                {{ strip_tags($right) }} <br>                                        
                                        @endforeach
                                    </td> -->
                                    <td>{{\App\Models\AssessmentQuestionAnswer::ASSESSMENT_TYPES[$assessmentQA->type]}}</td>
                                    <!-- <td>{{$assessmentQA->order}}</td> -->
                                    <td>{{ $assessmentQA->created_at ? date('d-m-Y', strtotime($assessmentQA->created_at)) : '' }} </td>
                                    <td class="text-right table-options">
                                        @php
                                            $canEditAssessmentQA = App\Repositories\AssessmentQARepository::canEdit($assessmentQA);
                                        @endphp
                                        <div class="btn-group btn-small">
                                            <a class="btn pr-2 pl-2 btn-outline text-info" 
                                                    href="{{ route('member.course.assessment-qa.edit',[$assessmentQA->id]) }}" 
                                                    data-provide="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>&nbsp;@lang('Edit')
                                            </a>
                                      
                                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" 
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                @php 
                                                    $hasSubmittedLongAnswers = $assessmentQA->type == 'long_answer' ? 
                                                            \App\Repositories\AssessmentQARepository::checkLongAnswerByCourseAndQuestionId($course->id,$assessmentQA->id) : false;
                                                @endphp
                                                    @if($hasSubmittedLongAnswers)
                                                        <a class="btn pr-2 pl-2 btn-outline text-success" 
                                                            href="{{ route('member.course.assess_long_answer.detail',[$course->id, $assessmentQA->id]) }}" 
                                                            data-provide="tooltip" title="Show">
                                                        <i class="fas fa-eye"></i>&nbsp;{{ __('View Submitted Long Answers') }}</a>
                                                    @endif
                                                    @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                            title="@lang('Cannot delete as the course already had course takers')">
                                                            <span class="btn pr-2 pl-1 btn-outline disabled text-danger"> 
                                                                <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                                            </span>
                                                        </span>
                                                    @else
                                                        {!! Form::open(array('route' => 
                                                            array('member.course.assessment-qa.destroy', $assessmentQA->id), 
                                                            'method' => 'delete', 'style' => 'display: inline', '' ,
                                                            'onsubmit' => 'return confirm("Are you sure you want to delete?");')) !!}
                                                            @if($canEditAssessmentQA)
                                                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" 
                                                                    title="Delete" type="submit" class="dropdown-item text-danger dropdown-delete"> 
                                                                    <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                                                </button>
                                                            @else 
                                                                <button class="btn btn-outline disabled">@lang('Delete')</button>
                                                            @endif
                                                            {{-- @if(!$canEditAssessmentQA) --}}
                                                                <!-- <a class="btn pr-2 pl-2 btn-outline text-success" 
                                                                    href="{{ route('member.course.assessment-qa.detail',[$assessmentQA->id]) }}" 
                                                                    data-provide="tooltip" title="Show">
                                                                <i class="fas fa-eye"></i>&nbsp;{{-- __('View Submitted Assessments') --}}</a> -->
                                                            {{-- @else --}}
                                                                <!-- <button class="btn btn-outline disabled">@lang('No submission for assessment yet!')</button> -->
                                                            {{--  @endif --}}
                                                        {!! Form::close() !!}
                                                    @endif
                                                </div>                                      
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">@lang('No Questions & Answers Yet')</td>            
                                </tr>          
                            @endif
                        
                    </tbody>
                </table>
            </div>      
        </div>
    </div>
@endif