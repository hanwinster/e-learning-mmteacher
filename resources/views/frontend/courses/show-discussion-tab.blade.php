
<div class="tab-pane  fade " id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
    @if(auth()->check())
        @if($course->allow_discussion)
            @php 
                $userName = auth()->user()->username; 
                $userId = auth()->user()->id;
                $discussionId = isset($discussion) ? $discussion->id: null;
                $isAllLearnersAllowed = isset($discussion) ? $discussion->allow_learners: null;
                $isCourseTakersAllowed = isset($discussion) ? $discussion->allow_takers	: null;
                $lastDiscussedTime = isset($discussion) && count($messages) ? count($messages) - 1 : null; 
            @endphp
            <div id="chat-room" class="container-fluid mt-3">
                <div class="row clearfix">
                    <div class="col-12">
                        <div class="card chat-app">
                            <div class="chat">
                                <div class="chat-header clearfix">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <!-- <a data-toggle="modal" data-target="#view_info">
                                                <i class="fas fa-bullhorn fa-lg"></i>
                                            </a> -->
                                            <div class="chat-about">
                                                <h6 class="m-b-0">
                                                    @if(isset($discussion->title))
                                                        {{strip_tags($discussion->title)}}
                                                    @else
                                                        -
                                                    @endif
                                                </h6>
                                                <p class="mt-2 mb-2">
                                                    @if(isset($discussion->description))
                                                        {!! $discussion->description !!}
                                    
                                                    @endif
                                                </p>
                                                <small>{{__('Last discussion') }}:&nbsp;
                                                    @if($lastDiscussedTime !== null) 
                                                        {{ date('d-m-Y h:m', strtotime($messages[$lastDiscussedTime]['created_at'])) }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                                           
                                @if(count($messages))
                                    @if($amIParticipatedBefore) 
                                        <div class="chat-history">
                                            <ul class="m-b-0" id="messages">
                                                @foreach($messages as $idx => $cm) 
                                                    @if($cm['user_id'] == auth()->user()->id)
                                                        <li class="clearfix">
                                                            <div class="message-data">
                                                                <span class="message-data-time">
                                                                    {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                </span>
                                                            </div>
                                                            <div class="message my-message">{{ $cm['message'] }}</div>
                                                        </li>
                                                    @else
                                                        <li class="clearfix">
                                                            <div class="message-data text-right">
                                                                <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                @if(strpos($cm['avatar'], "/storage"))
                                                                    <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                @else
                                                                    <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                @endif    
                                                                <small class="message-data-time d-block me-5">
                                                                    {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                </small>                                                      
                                                            </div>
                                                            <!-- <small style="display:block;float:right;">
                                                                <i>{{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}<i>                                                         
                                                            </small> -->
                                                            
                                                            <div class="message other-message float-right clearifx"> 
                                                                {{ $cm['message'] }}
                                                            </div>
                                                            
                                                        </li>
                                                    @endif
                                                @endforeach                                                                       
                                            </ul>
                                        </div>
                                    @else 
                                        <div class="chat-history">  <!-- not participated before, other people' chat -->
                                            <ul class="m-b-0" id="messages">
                                                @foreach($messages as $idx => $cm)                                                   
                                                    @if($idx == 0) 
                                                        <li class="clearfix">
                                                            <div class="message-data text-right">
                                                                <span class="message-data-time">{{ $cm['username'] }}</span>
                                                                @if(strpos($cm['avatar'], "/storage"))
                                                                    <img src="{{ asset( $cm['avatar'] ) }}" alt="{{ $cm['username'] }}">
                                                                @else
                                                                    <img src="{{ $cm['avatar'] }}" alt="{{ $cm['username'] }}">
                                                                @endif  
                                                                <small class="message-data-time d-block me-5">
                                                                    {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                </small>
                                                            </div>
                                                            <div class="message alt-l-message float-right"> {{ $cm['message'] }}</div>
                                                        </li>
                                                        @php $lastPosition = 'right'; @endphp
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
                                                                        <small class="message-data-time d-block ">
                                                                            {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                        </small>  
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
                                                                        <small class="message-data-time d-block me-5">
                                                                            {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                        </small>                                                          
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
                                                                        <small class="message-data-time d-block ">
                                                                            {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                        </small>                                                       
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
                                                                        <small class="message-data-time d-block ">
                                                                            {{ date('d-m-Y h:m', strtotime($cm['created_at'])) }}
                                                                        </small>
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
                                    @endif
                                @else
                                    <div class="chat-history">
                                        <ul class="m-b-0" id="messages">
                                            
                                        @if( $isAllLearnersAllowed || 
                                            ( $isCourseTakersAllowed && \App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course) ) )
                                            @lang('No discussion for this board yet! Be the first one to participate')
                                            
                                        @else                                       
                                            @lang('No discussion for this board yet!')
                                        @endif
                                        </ul>
                                    </div>
                                @endif
                                @php
                                    $hasMessages = count($messages) ? true : false;
                                @endphp
                                @if(\App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course)
                                || $amICourseOwner || $isAllLearnersAllowed)
                                    <form id="message-form" class="form-control" style="padding:0; border-radius:0">     
                                        <input type="hidden" name="userid" id="userid" value="{{$userId}}" />
                                        <input type="hidden" name="userName" id="userName" value="{{$userName}}" />
                                        <input type="hidden" name="discussionId" id="discussionId" value="{{$discussionId}}" />
                                        <input type="hidden" name="isParticipatedBefore" id="isParticipatedBefore" value="{{$amIParticipatedBefore}}" />
                                        <input type="hidden" name="hasMessages" id="hasMessages" value="{{$hasMessages}}" />  
                                        <!-- <div class="typing-indicator d-none">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>  -->
                                        <div class="clearfix"> 
                                            
                                            <!-- <span>{{ __("Someone is typing....")}}</span> -->
                                            <div class="input-group mb-0" style="border-radius: 0; ">               
                                                <input type="text" name="message" class="form-control" id="message-input" 
                                                        placeholder="@lang('Type here...')" style="width:90%;border-radius:0;"/>
                                                <div class="input-group-append" style="border-radius: 0;background-color: #fff;">
                                                    <button class="input-group-text text-primary" type="submit" 
                                                            style="border-radius: 0;background-color: #fff;">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>  
                                @endif                                                       
                            </div>
                        </div>
                    </div>
                </div>
            </div>             
        
        @else
            <h6 class="info-text"><i class="fas fa-exclamation-circle"></i>&nbsp;
                {{ __('Currently discussions are not allowed for this course') }}
            </h6>
        @endif
       
    @else
        <div id="chat-login" class="container-fluid pt-3">
            <h6>{{__('Please login to view the discussion and chat with the course takers.') }}</h6>
            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">{{__('Login') }}</a>
        </div>
    @endif
</div>