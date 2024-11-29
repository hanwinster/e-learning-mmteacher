@extends('backend.layouts.default')

@section('title', __('Course Level'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Course Levels') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                @can('add_course_category')
                                    <a href="{{ route('admin.course-level.create') }}" 
                                        class="btn btn-primary btn-md">{{ _('New') }}</a>
                                     @endcan    
                            </h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.course-level.index') }}" method="get">
                                    
                                    <input name="search" placeholder="{{ _('Search') }}" class="form-control top-input" type="text" value="{{ request('search') }}">
                                    
                                    <button class="btn btn-primary btn-md">{{ _('Search') }}</button>
                                    <a href="{{ route('admin.course-level.index') }}" class="btn btn-secondary btn-md">
                                        {{ _('Reset') }}
                                    </a>                                
                                </form>
                            </div>                        
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40">@sortablelink('id', __('ID'))</th>
                                        <th>@sortablelink('name', __('Level Name '))</th>
                                        <th>@sortablelink('value', __('Level Value '))</th>
                                        <th>@sortablelink('updated_at', __('Updated At'))</th>
                                        <th class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->name }}</td>
                                        <td>{{ $post->value }}</td>
                                        <td>{{ $post->updated_at }}</td>
                                        <td class="text-center">
                                            @can('edit_course_category')
                                                <a class="table-action hover-primary cat-edit" 
                                                    href="{{ route('admin.course-level.edit', $post->id) }}" 
                                                    data-provide="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete_course_category')
                                                {!! Form::open(array('route' => array('admin.course-level.destroy', $post->id),
                                                    'method' => 'delete' , 
                                                    'onsubmit' => 'return confirm("Are you sure you want to delete?");',
                                                    'style' => 'display: inline', '')) !!}
                                                    <button data-provide="tooltip" data-toggle="tooltip" title="Delete" 
                                                        type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
                                                        <i class="fas fa-trash"></i>
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
                </div>
            </div>
        </div>
    </section>
</div>
@endsection