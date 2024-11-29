@extends('backend.layouts.default')

@section('title', __('Resources'))

@section('content')
<div class="card">
    <header class="card-header">

        <h4 class="card-title">
            @can('add_resource')
            <!--<a href="{{ route('admin.resource.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>-->
            @endcan
        </h4>

        <form action="{{ route('admin.resource.index') }}" method="get">
            <div class="card-header-actions">

                <div class="lookup lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>

                {!! Form::select('subject_id', $subjects, Request::get('subject_id'), ['class'
                => 'form-control']) !!}

                {!! Form::select('uploaded_by',
                $uploaded_by, request('uploaded_by'), ['class' => 'form-control', 'placeholder' => '-Uploaded By-' ])
                !!}

                {!! Form::select('approval_status',
                $approvalStatus, request('approval_status'), ['class' => 'form-control', 'placeholder' => '-Select
                Status-' ]) !!}

                {!! Form::select('resource_format', ['' => '- Select Format -'] + $formats,
                Request::get('resource_format'), ['class' => 'form-control']) !!}

                <button class="btn btn-primary">{{ __('Search') }}</button>
                <a href="{{ route('admin.resource.index') }}" class="btn">{{ __('Reset') }}</a>
            </div>
        </form>
    </header>

    <div class="card-body">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
            <thead>
                <tr>
                    <th width="60">@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('title', __('Title'))</th>
                    <th>{{__('Image') }}</th>
                    <th>@sortablelink('user_id', __('Uploaded By'))</th>
                    <th>{{ __('Published') }}</th>
                    <th>{{ __('Approval Status') }}</th>
                    <th>@sortablelink('approved_by', 'Approved By')</th>
                    <th>@sortablelink('created_at', 'Created At')</th>
                    <th width="150" class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>
                        <div class="text-center">
                        @if ($img_url = $post->getThumbnailPath())
                        @php
                        $images = $post->getMedia('resource_cover_image');
                        @endphp
                        @foreach($images as $image)
                        <!--<img src="{{ $img_url }}" alt="{{ $post->title }}">-->
                        <img src="{{ asset($image->getUrl('thumb')) }}">
                        @endforeach
                        @else
                        -
                        @endif
                        <br>{{ $post->getResourceFormat() }}
                        </div>
                    </td>
                    <td>{{ $post->user->name ?? '' }}</td>
                    <td class="text-center">
                        {!! $post->published ? 'Yes': 'No' !!}
                    </td>
                    <td>{{ $post->approved_at }}
                        <div class="text-success">
                            ({{ $post->getApprovalStatus() ?? __('Not submitted yet')}})
                        </div>
                    </td>
                    <td>{{ $post->approver->name ?? '' }} </td>
                    <td>{{ $post->created_at ?? '' }} </td>
                    <td class="text-right table-options">
                        <div class="btn-group btn-small">
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('resource.preview', $post->id) }}" data-provide="tooltip"
                                        title="Preview"><i
                                        class="ti-eye"></i></a>
                                @can('edit_resource')
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.resource.edit', $post->id) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i> {{ __('Edit') }} </a>
                                @endcan
                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a onclick="return confirm('{{ __('Are you sure you want to create new version?') }}')" href="{{ route('admin.resource.new-version', $post->id) }}" class="dropdown-item"><i class="ti-file"></i> {{ __('New Version') }}</a>
                                <a href="{{ route('admin.resource.related', $post->id) }}" class="dropdown-item"><i class="ti-link"></i> {{ __('Related Resources') }}</a>
                                @can('delete_resource')
                                {!! Form::open(array('route' => array('admin.resource.destroy', $post->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: grab" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                <i class="ti-trash"></i>
                                {{ __('Delete') }}
                                </button>
                                {!! Form::close() !!}
                                @endcan
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
