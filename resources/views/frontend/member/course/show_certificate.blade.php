<div class="row mt-3">
    <div class="col-12 ">
        @if($course->course_type_id ==1)
            @if(!isset($certificate->id))
                @if(!\App\Repositories\CoursePermissionRepository::canAdd($course)) 
                    <a href="{{route('member.course.certificate.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
                @endif
            @else
                <a href="{{route('member.course.certificate.preview', $certificate->id)}}" class="btn btn-outline-primary btn-md mr-2">
                    <i class="fas fa-eye"></i>&nbsp;{{__('Preview Certificate')}}&nbsp;&nbsp;
                <a href="{{route('member.course.certificate.download', $certificate->id)}}" class="btn btn-outline-primary btn-md">
                    <i class="fas fa-eye"></i>&nbsp;{{__('Download Certificate')}}
            @endif
        @else
            {{ __('You have chosen non-certified type for this course!')}}
        @endif
        
        </a>
    </div>
    @if($course->course_type_id ==1)
        <div class="col-12 mt-3">
            <h5>{{ __('Certificate Setting') }}</h5>
            @if(isset($certificate->id))
                <table class="table no-footer">           
                    <tr>
                        <td>@lang('Title of Certificate')</td>
                        <td>@if($certificate->title)
                                {{$certificate->title}}
                            @else
                                -
                            @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('Certify Text')</td>
                        <td>
                            @if(($certificate->certify_text))
                                {{ $certificate->certify_text }} 
                            @else 
                            -
                            @endif
                        </td>
                    </tr>  
                    <tr>
                        <td>@lang('Completion Text')</td>
                        <td>
                            @if(($certificate->completion_text))
                                {{ $certificate->completion_text }} 
                            @else 
                            -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>@lang('Description')</td>
                        <td>
                            @if(($certificate->description))
                                {{ $certificate->description }} 
                            @else 
                            -
                            @endif
                        </td>
                    </tr>                   
                </table>
                <div class="px-3">
                    @if($canEdit)
                        <a href="{{route('member.course.certificate.edit', $certificate->id)}}" class="btn btn-primary btn-sm">@lang('Edit')</a>    
                    @endif               
                </div>
            @else
            <table class="pt-1">
                <tr>
                    <td colspan="6"><div class="text-center">@lang('No records.')</div></td>
                </tr>
            </table>
            @endif
        </div>
    @endif
</div>