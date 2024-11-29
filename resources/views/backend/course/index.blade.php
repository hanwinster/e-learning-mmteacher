@extends('backend.layouts.default')

@section('title', __('Courses'))

@section('content')
<div class="card">
    <header class="card-header">

        <h4 class="card-title">
            @can('add_course')
            <a href="{{ route('admin.course.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>
            @endcan
        </h4>

        <form action="{{ route('admin.course.index') }}" method="get">
            <div class="card-header-actions">

                <div class="lookup lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>

                {!! Form::select('course_category_id', $categories, Request::get('course_category_id'), ['class'
                => 'form-control']) !!}
                {!! Form::select('level_id', $levels,
                Request::get('level_id'), ['class' => 'form-control']) !!}

                {!! Form::select('uploaded_by',
                $uploaded_by, request('uploaded_by'), ['class' => 'form-control', 'placeholder' => '-Uploaded By-' ])
                !!}

                {!! Form::select('approval_status',
                $approvalStatus, request('approval_status'), ['class' => 'form-control' ]) !!}

                <button class="btn btn-primary">{{ __('Search') }}</button>
                <a href="{{ route('admin.course.index') }}" class="btn">{{ __('Reset') }}</a>
            </div>
        </form>
    </header>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer" style="width: 100%;">
            <thead>
                <tr>
                    <th width="60">{{ __('No#') }}</th>
                    <th>@sortablelink('title', __('Course Title '))</th>
                    <th>{{ __('Course Category') }}</th>
                    <th>{{ __('Cover Image') }}</th>
                    <th>{{__('Course Level') }}</th>
                    <th>@sortablelink('user_id', __('Uploaded By '))</th>
                    <th>{{ __('Published') }}</th>
                    <th>{{ __('Approval Status') }}</th>
                    <th>@sortablelink('created_at', __('Created At'))</th>
                    <th>@sortablelink('approved_by', 'Approved')</th>
                    <th width="150" class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($posts as $key => $post)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td>
                        @if ($img_url = $post->getThumbnailPath())
                            @php
                                $images = $post->getMedia('course_cover_image');
                            @endphp
                            @foreach($images as $image)
                                <img src="{{ asset($image->getUrl('thumb')) }}">
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{ $post->getLevel() }}
                    </td>
                    <td>{{ $post->user->name ?? '' }}</td>
                    <td class="text-center">
                        {!! $post->is_published ? 'Yes': 'No' !!}
                    </td>
                    <td>{{ $post->approved_at }}
                        <div class="text-success">
                            ({{ $post->getApprovalStatus() ?? __('Not submitted yet')}})
                        </div>
                    </td>
                    <td class="text-center">
                        {!! $post->created_at ?? '' !!}
                    </td>
                    <td></td>
                    <td class="text-right table-options">
                        <div class="btn-group btn-small">
                                @can('detail_course')
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.course.show', $post->id) }}" data-provide="tooltip"
                                        title="Show"><i
                                        class="ti-eye"></i></a>
                                @endcan
                                @can('edit_course')
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.course.edit', $post->id) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i>  </a>
                                @endcan
                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @can('delete_course')
                                {!! Form::open(array('route' => array('admin.course.destroy', $post->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                <i class="ti-trash"></i>
                                {{ __('Delete') }}
                                </button>
                                {!! Form::close() !!}
                                @endcan
                                @if(count($post->courseLearners) != 0)
                                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{ route('admin.take-course-user', $post->id) }}" data-provide="tooltip" title="View Take Course Users"><i class="ti-eye"></i> {{ __('Take Course User') }} </a>
                                @endif
                                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{ route('admin.lecture.create', $post->id) }}" data-provide="tooltip" title="New Lecture"><i class="ti-plus"></i> {{ __('New Lecture') }} </a>
                                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{ route('admin.quiz.create', $post->id) }}" data-provide="tooltip" title="New Quiz"><i class="ti-plus"></i> {{ __('New Quiz') }} </a>
                                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{ route('admin.assignment.create', $post->id) }}" data-provide="tooltip" title="New Assignment"><i class="ti-plus"></i> {{ __('New Assignment') }} </a>
                            </div>
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
@endsection
