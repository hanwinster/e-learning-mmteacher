@extends('backend.layouts.default')

@section('title', __('Courses'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Manage Courses') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">  
                    @include('layouts.form_alert')  
                    <div class="col-12 mx-auto">
                        <h4 class="mb-2">
                            {{ __('Manage Courses') }}&nbsp;
                            <a href="{{ route('member.course.create') }}" class="btn btn-primary btn-md">{{ __('Create a new course') }}</a>
                        </h4>
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                
                                <form action="{{ route('member.course.index') }}" method="get">
                                    <div class="card-header-actions">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="lookup lookup-right d-none d-lg-block">
                                                    <input name="search" class="form-control" placeholder="@lang('Search')" 
                                                    type="text" value="{{ request('search') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                {!! Form::select('course_category_id', $categories, Request::get('course_category_id'), ['class'=> 'form-control']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                {!! Form::select('level_id', $levels, Request::get('level_id'), ['class' => 'form-control']) !!}
                                            </div>

                                            @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                                <div class="col-md-3">
                                                    {!! Form::select('uploaded_by',
                                                        $uploaded_by, request('uploaded_by'), ['class' => 'form-control', 'placeholder' => __('-Uploaded By-') ])
                                                    !!}
                                                </div>
                                            @elseif( auth()->user()->isTeacherEducator() )
                                                <div class="col-md-3">
                                                    {!! Form::select('approval_status', $approvalStatus, request('approval_status'), 
                                                        ['class' => 'form-control', 'placeholder' => '-Select Status-' ]) !!}
                                                </div>    
                                            @endif

                                        </div>
                                        <div class="row mt-2">
                                            @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                                <div class="col-md-3">
                                                    {!! Form::select('approval_status', $approvalStatus, request('approval_status'), 
                                                        ['class' => 'form-control', 'placeholder' => '-Select Status-' ]) !!}
                                                </div>
                                            @endif
                                            <div class="col-12 col-md-3">
                                                <button class="btn btn-primary btn-md">{{ __('Search') }}</button>                                             
                                                <a href="{{ url('profile/course') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="card-body table-responsive mt-1">
                                <table class="table table-bordered no-footer table-top-border">
                                    <thead>
                                        <tr>
                                            <th class="fixed-w-60">{{ __('No#') }}</th>
                                            <th>@sortablelink('title', __('Course Title'))</th>
                                            <th>{{ __('Course Category') }}</th>
                                            <th>{{ __('Course Level') }}</th>
                                            <th>{{ __('Approval Status') }}</th>
                                            <th>{{ __('Published') }}</th>
                                            <th>{{ __('Uploaded By') }}</th>
                                            <th>@sortablelink('created_at', __('Created At'))</th>
                                            <th width="100px" class="text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($posts as $key => $post)                                    
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{!! strip_tags($post->title) !!}</td>
                                            <td>
                                                @foreach($post->course_categories as $idx=>$category)
                                                    {{ __($categories[$category]) }} 
                                                    @if($idx !== count($post->course_categories) -1 ) , @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                {{--<div class="text-center">
                                                    @if (!empty($post->cover_image))
                                                        <img src="{{ asset('assets/course/cover_image/'.$post->id.'/'.$post->cover_image) }}" 
                                                            width="100" height="80">
                                                    @endif
                                                </div>--}}
                                                {{__($post->getLevel()) }}
                                            </td>
                                            <td>
                                                @php 
                                                    $hasValidationError = \App\Repositories\CourseRepository::validateCourseBeforeRequest($post); 
                                                @endphp       
                                                @if($hasValidationError)
                                                    {{ __('Not Ready to submit') }}
                                                @else
                                                    @if ($post->approval_status === null)                                                   
                                                        {{ __('Ready to submit') }}
                                                    @else
                                                        {{ __($post->getApprovalStatus()) }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $post->is_published ? __('Yes'): __('No') !!}
                                            </td>
                                            <td>
                                                @if( isset($post->user->name))
                                                <a href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->name ?? '' }}</a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $post->created_at ? date('d-m-Y', strtotime($post->created_at )) : '' !!}
                                            </td>
                                            <td>
                                                <div class="btn-group btn-sm">
                                                    @php
                                                        $canEdit = App\Repositories\CoursePermissionRepository::canEdit($post);
                                                    @endphp
                                                    <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.course.show', $post->id) }}" data-provide="tooltip"
                                                            title="View">
                                                            <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                                title="@lang('View')"><i class="fas fa-eye"></i></span>
                                                    </a>
                                                     @if ($canEdit)
                                                        <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.course.edit', $post->id) }}" data-provide="tooltip"
                                                            title="Edit">
                                                            <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                                                title="@lang('Edit')"><i class="fas fa-edit"></i></span>
                                                        </a>
                                                    @endif
                                                    @if($hasValidationError)
                                                        <a class="btn pr-2 pl-2 btn-outline">
                                                            <span class="tooltip-info text-secondary" data-toggle="tooltip" data-placement="top" 
                                                                title="{{ $hasValidationError }}"><i class="fas fa-paper-plane"></i></span>
                                                        </a>
                                                    @else
                                                        @if (   isset($post->id) && $post->is_published != 1 && 
                                                                (auth()->user()->id == $post->user_id) && 
                                                                ( !auth()->user()->isAdmin() ) && ( !auth()->user()->isUnescoManager() ) &&
                                                                ( $post->approval_status == 0 || $post->approval_status == 2 || $post->is_published != 1)  
                                                                && $post->isRequested == 0
                                                            )  
                                                            <a class="btn pr-2 pl-1 btn-outline" href="{{ route('member.course.submit-request', $post->id) }}" 
                                                                data-provide="tooltip" title="Request for Approval" >
                                                                <span class="tooltip-info text-warning" data-toggle="tooltip" data-placement="top" 
                                                                    title="@lang('Request Approval')"><i class="fas fa-paper-plane"></i></span>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    @if ($canEdit)
                                                        <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle pr-3 pl-2" 
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @if($post->user_id === auth()->user()->id)
                                                                {!! Form::open(array('route' => array('member.course.destroy', $post->id), 'method' => 'delete'
                                                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                                                    <button data-provide="tooltip" data-toggle="tooltip" title="Delete" 
                                                                        type="submit" class="dropdown-item text-danger dropdown-delete">
                                                                        <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                                                    </button>
                                                                {!! Form::close() !!}
                                                            @endif
                                                            @if(count($post->courseLearners) != 0)
                                                                <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.take-course-user', $post->id) }}" 
                                                                data-provide="tooltip" title="View Take Course Users"><i class="fas fa-eye"></i> {{ __('Course Takers') }} </a>
                                                            @endif
                                                            {{-- <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.clone-course', $post->id) }}" 
                                                                data-provide="tooltip" title="Will clone the contents only"><i class="fas fa-copy"></i> {{ __('Clone') }} </a> --}}
                                                            <a class="btn pr-2 pl-1 btn-outline dropdown-item text-success" href="{{ route('member.lecture.create', $post->id) }}" 
                                                            data-provide="tooltip" title="New Lecture" ><i class="fas fa-plus"></i> {{ __('New Lecture') }} </a>
                                                            <a class="btn pr-2 pl-1 btn-outline dropdown-item text-success" href="{{ route('member.quiz.create', $post->id) }}" 
                                                            data-provide="tooltip" title="New Quiz" ><i class="fas fa-plus"></i> {{ __('New Quiz') }} </a>
                                                            <a class="btn pr-2 pl-1 btn-outline dropdown-item text-success" href="{{ route('member.course.assessment-qa.create', $post->id) }}" 
                                                            data-provide="tooltip" title="New Assessment" ><i class="fas fa-plus"></i> {{ __('New Assessment') }} </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <footer class="card-footer text-center">
                                {{ $posts->links() }}
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
