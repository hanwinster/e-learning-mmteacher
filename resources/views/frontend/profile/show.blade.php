@extends('frontend.layouts.default')
@section('title', $user->name)

@section('content')
<section class="page-section mt-5" id="category-courses">
    <div class="container pt-1">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{__('Profile')}}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- end of breadcrumb -->

    <div class="container pt-5">
        <div class="row">
                <div class="col-12 col-md-3">
                    @if($profileThumbnail = optional($user->getMedia('profile')->first())->getUrl('medium'))
                        <div class="form-group col-xs-12">
                                <img class="thumbnail" src="{{ $profileThumbnail }}" height="auto" width="80%">
                            
                        </div>
                    @else
                            <div class="form-group col-xs-12">
                                <img class="thumbnail" src="{{ asset('/assets/img/avatar.png') }}">
                            </div>
                    @endif
                </div>
                <div class="col-12 col-md-9 mt-3">
                    <h3>{{ $user->name }} <small>({{ '@'.$user->username }})</small></h3>
                    @if( $user->type == \App\User::TYPE_TEACHER_EDUCATOR || 
                        ($user->type == \App\User::TYPE_MANAGER && !$user->is_unesco_mgr) )
                        <div><span class="text-primary">{{ __('Education College') }}</span> : {{ $user->college->title ?? '' }}</div>
                        <div><span class="text-primary">{{ __('Subjects') }}</span> :
                        @if ($subjects = $user->subjects)
                            @foreach($subjects as $subject)
                                {{ $subject->title }}@if (!$loop->last), @endif
                            @endforeach
                        @endif
                        </div>
                    @endif
                    <div><span class="text-primary">{{ __('User Type') }}</span> : {{ Illuminate\Support\Str::title(str_replace('_', ' ', $user->user_type)) }}</div>
                    <div><span class="text-primary">{{ __('Member Since') }}</span> : {{ $user->created_at->format('M d, Y') }}</div>
                    @php $lastActive = date_create($user->last_login); @endphp
                    <div><span class="text-primary">{{ __('Last Active On') }}</span> : {{ date_format($lastActive, 'M d,Y') }}</div>
                </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-md-3">
            </div>
            <div class="col-12 col-md-9 mb-2">
                <h6>
                    {{__('Courses Created By ')}}&nbsp;{{$user->name}}
                </h6>
            </div>
            <div class="col-12 col-md-3">
            </div>
            <div class="col-12 col-md-9 table-responsive">
                <table class="table table-bordered"> 
                    <tr>
                        <td>{{__('No.')}}</td>
                        <td>{{__('Course Title')}}</td>
                        <td>{{__('Status')}}</td>
                        <td>{{__('Enrollments')}}</td>
                        <td>{{__('Created On')}}</td>
                    </tr>
                   
                        @foreach($courses as $idx => $course)
                            <tr>
                                <td>
                                    {{$idx+1}}
                                </td>
                                <td>
                                <a href="{{ route('courses.show', $course ) }}">{{ strip_tags($course->title)}}</a>
                                </td>
                                <td>
                                    @if($course->published)
                                        {{__('Published')}}
                                    @else
                                        @if($course->approval_status == 1)
                                            {{__('Approved')}}
                                        @elseif($course->approval_status ==2)
                                            {{__('Rejected')}}
                                        @else
                                            {{__('Pending')}}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if(count($course->learners) > 0)
                                        {{count($course->learners)}}
                                    @else
                                        -
                                    @endif
                                    
                                </td>
                                <td>
                                    {{$course->created_at->format('M d, Y')}}
                                </td>
                            </tr>
                        @endforeach
                    
                </table>
            </div>
        </div>
    </div>
</section>

@endsection
