<div class="card">
    @if(!$course->allow_discussion)
        <div class="card-header bg-white">
            <h4 class="card-title">
                @lang('Discussion board is disabled for this course. Please modify Allow Discussion option in course info to enable discussion')
            </h4>
        </div>
    @else
        <div class="card-header bg-white">
            <h4 class="card-title">
            @if( isset($discussion->id) && $canEdit)                   
                <a href="{{route('member.course.discussion.edit', $discussion->id)}}" class="btn btn-primary text-white pull-right">{{__('Edit')}}</a>                     
            @else
                @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                        title="@lang('Cannot create new as the course owner locked it')">
                        <a class="btn btn-secondary disabled text-white pull-right">
                    {{__('New')}}
                    </a></span>
                @else
                    <a href="{{route('member.course.discussion.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
                @endif
            @endif
            </h4>  
        </div>
        <div id="discussion-config-container" class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" id="nav-dis-configure-tab" role="tab" aria-controls="nav-dis-configure" 
                    aria-selected="true" href="#nav-dis-configure">@lang('Discussion Board Configuration')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" id="nav-dis-view-tab" role="tab" aria-controls="nav-dis-view" 
                    aria-selected="false" href="#nav-dis-view">@lang('View Discussion History')</a>
                </li>
            </ul>
            <div class="tab-content" id="discussion-config-content">
                <!-- configure tab -->
                <div class="tab-pane fade active show" id="nav-dis-configure" role="tabpanel" aria-labelledby="nav-dis-configure-tab">
                    <div class="row">
                        <div class="col-12">
                            @if(isset($discussion->id))
                            <table class="table no-footer" style="border: none;">
                                <tr>
                                    <td>@lang('Title')</td>
                                    <td>
                                        @if(isset($discussion->title))
                                            {{ strip_tags($discussion->title) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>@lang('Description')</td>
                                    <td>
                                        @if(isset($discussion->description))
                                            {!! strip_tags($discussion->description) !!}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>@lang('Allow All Course Takers To Discuss')</td>
                                    <td>@if($discussion->allow_takers == 1)
                                            @lang('Yes')
                                        @else
                                            @lang('No')
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                <td>@lang('Allow All Learners To Discuss')</td>
                                    <td>@if($discussion->allow_learners == 1)
                                            @lang('Yes')
                                        @else
                                            @lang('No')
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @else
                                <p class="info-text mt-3">@lang('No Configuration Created Yet!')</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- discussion table tab -->
                <div class="tab-pane fade" id="nav-dis-view" role="tabpanel" aria-labelledby="nav-dis-view-tab">
                    <div id="chat-room-admin" class="container-fluid mt-3">
                        <div class="row clearfix">
                            <div class="col-12">
                                <div class="card chat-app">
                                    <div class="chat">
                                        <div class="chat-header clearfix">
                                            <div class="row">
                                                <div class="col-12">
                                                    @php 
                                                        $userName = auth()->user()->username; 
                                                        $userId = auth()->user()->id;
                                                        $discussionId = isset($discussion) ? $discussion->id: null;
                                                        $lastDiscussedTime = isset($discussion) && count($messages) ? count($messages) -1 : null;
                                                    @endphp
                                                    <div class="chat-about">
                                                        <h6 class="m-b-0">
                                                            @if(isset($discussion->title))
                                                                {{ strip_tags($discussion->title) }}
                                                            @else
                                                                -
                                                            @endif&nbsp;&nbsp;
                                                        </h6>
                                                        <small>{{__('Last discussion:') }}&nbsp;
                                                            @if($lastDiscussedTime)
                                                                {{ date('d-m-Y h:m', strtotime($messages[$lastDiscussedTime]['created_at'])) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </small>&nbsp;&nbsp;
                                                        <small>@lang('Total Participants'): 
                                                            @if(count($participants) > 1)
                                                                {{count($participants)}}&nbsp;@lang('people')
                                                            @elseif(count($participants) == 1)
                                                                {{count($participants)}}&nbsp;@lang('person')
                                                            @else
                                                                @lang('No participant yet')
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-history">
                                            <ul class="m-b-0">
                                                @foreach($messages as $idx => $cm) 
                                                    
                                                    @if($idx == 0) 
                                                        <li class="clearfix">
                                                            <div class="message-data text-left">
                                                                <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                @if(strpos($cm['avatar'], "/storage"))
                                                                    <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                @else
                                                                    <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                @endif  
                                                            </div>
                                                            <div class="message alt-l-message float-left"> {{ $cm['message'] }}</div>
                                                        </li>
                                                        @php $lastPosition = 'left'; @endphp
                                                    @else
                                                        @if( $messages[$idx]['username'] == $messages[$idx-1]['username']) 
                                                        <!-- same user & need to be displayed in the same side of last msg-->
                                                            @if($lastPosition == 'left')
                                                                <li class="clearfix">
                                                                    <div class="message-data text-left">
                                                                        <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                        @if(strpos($cm['avatar'], "/storage"))
                                                                            <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                        @else
                                                                            <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                        @endif  
                                                                    </div>
                                                                    <div class="message alt-l-message float-left"> {{ $cm['message'] }}</div>
                                                                </li>
                                                                @php $lastPosition = 'left'; @endphp
                                                            @else
                                                                <li class="clearfix">
                                                                    <div class="message-data text-right">
                                                                        <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                        @if(strpos($cm['avatar'], "/storage"))
                                                                            <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                        @else
                                                                            <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                        @endif                                                          
                                                                    </div>
                                                                    <div class="message alt-r-message float-right"> 
                                                                        {{ $cm['message'] }}
                                                                    </div>                                                 
                                                                </li>
                                                                @php $lastPosition = 'right'; @endphp
                                                            @endif
                                                        @else
                                                        <!-- different user and need to be displayed different side of last msg-->
                                                            @if($lastPosition == 'left')
                                                                <li class="clearfix">
                                                                    <div class="message-data text-right">
                                                                        <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                        @if(strpos($cm['avatar'], "/storage"))
                                                                            <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                        @else
                                                                            <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                        @endif                                                          
                                                                    </div>
                                                                    <div class="message alt-r-message float-right"> 
                                                                        {{ $cm['message'] }}
                                                                    </div>                                                 
                                                                </li>
                                                                @php $lastPosition = 'right'; @endphp
                                                            @else
                                                                <li class="clearfix">
                                                                    <div class="message-data text-left">
                                                                        <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                        @if(strpos($cm['avatar'], "/storage"))
                                                                            <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                        @else
                                                                            <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                        @endif  
                                                                    </div>
                                                                    <div class="message alt-l-message float-left"> {{ $cm['message'] }}</div>
                                                                </li>
                                                                @php $lastPosition = 'left'; @endphp
                                                            @endif
                                                        @endif
                                                    
                                                       
                                                    @endif
                                                    
                                                @endforeach                                                                                           
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>