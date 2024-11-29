@extends('backend.layouts.default')

@section('title', 'Categories')

@section('content')
<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_category')
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary pull-right">New</a>
            @endcan
        </h4>
        <form action="{{ route('admin.category.index') }}" method="get">
            <div class="card-header-actions">
                <div class="lookup lookup-sm lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>
                <button class="btn btn-sm btn-primary">Search</button>
                <a href="{{ route('admin.category.index') }}" class="btn btn-sm">Reset</a>
            </div>
        </form>
    </header>

    <div class="card-body">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
            <thead>
                <tr>
                    <th class="text-center">@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('name', 'Name')</th>
                    <th>Slug</th>
                    <th>Published</th>
                    <th width="150" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->name }}</td>
                    <td>{{ $post->slug }}</td>
                    <td>{{ $post->term_order }}</td>
                    <td class="text-center">
                        {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                    </td>
                    <td class="text-right table-options">
                        @can('edit_category')
                        <a class="table-action hover-primary cat-edit" href="{{ route('admin.category.edit', $post->id) }}" data-provide="tooltip"
                            title="Edit"><i class="ti-pencil"></i></a>
                        @endcan

                        @can('delete_category')
                            {!! Form::open(array('route' => array('admin.category.destroy', $post->id), 'method' => 'delete' , 'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
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