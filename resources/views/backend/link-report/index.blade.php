@extends('backend.layouts.default')

@section('title', __('Link Reports'))

@section('content')
    <div class="card">
        <header class="card-header">
            <h4 class="card-title">
               &nbsp;
            </h4>
            <form action="{{ route('admin.link-report.index') }}" method="get">
                <div class="card-header-actions">
                    <div class="lookup lookup-right d-none d-lg-block">
                        <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-primary">{{ __('Search') }}</button>
                    <a href="{{ route('admin.link-report.index') }}" class="btn">{{ __('Reset') }}</a>
                </div>
            </form>
        </header>

        <div class="card-body">
            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                <thead>
                <tr>
                    <th width="60">@sortablelink('id', __('ID'))</th>
                    <th>@sortablelink('resource.title', __('Resource'))</th>
                    <th>@sortablelink('user_id', __('User'))</th>
                    <th>@sortablelink('report_type', __('Report Type'))</th>
                    <th width="100">{{ __('Status') }}</th>
                    <th>@sortablelink('updated_at', __('Updated At'))</th>
                    <th width="160" class="text-center">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <a title="Click here to view Resource" href="{{ route('resource.show', $post->resource->slug) }}">
                            {{ $post->resource->title ?? '' }}
                            </a>
                        </td>
                        <td>
                            <a title="Click here to view User Profile" href="{{ route('profile.show', $post->user->username) }}">
                            {{ $post->user->name ?? '' }}
                            </a>
                        </td>
                        <td>
                            {{ $post->getTypeName() }}
                        </td>
                        <td class="text-center">
                            {{ $post->getStatusName() }}
                        </td>
                        <td>{{ $post->updated_at }}</td>
                        <td class="text-right table-options">
                            @can('edit_link_report')
                                <a class="table-action hover-primary cat-edit"
                                   href="{{ route('admin.link-report.edit', $post->id) }}" data-provide="tooltip"
                                   title="Edit"><i class="ti-pencil"></i></a>
                            @endcan

                            @can('delete_link_report')
                                {!! Form::open(array('route' => array('admin.link-report.destroy', $post->id), 'method' => 'delete' , 'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
                                    <i class="ti-trash"></i>

                                </button>
                                {!! Form::close() !!}
                            @endcan
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
