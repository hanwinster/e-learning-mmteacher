@extends('backend.layouts.default')

@section('title', __('Years'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Education Colleges') }}</li>
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
                @include('layouts.form_alert')
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                @can('add_year')
                                    <a href="{{ route('admin.year.create') }}" class="btn btn-primary btn-md">{{ __('New') }}</a>
                                @endcan
                            </h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.year.index') }}" method="get">
                                    <input name="search" placeholder="Search" type="text" class="form-control top-input" value="{{ request('search') }}">
                                    <button class="btn btn-primary btn-md">{{ __('Search') }}</button>
                                    <a href="{{ route('admin.year.index') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>                              
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="60">@sortablelink('id', __('ID'))</th>
                                    <th>@sortablelink('title', __('Title'))</th>
                                    <th width="100">{{ __('Published') }}</th>
                                    <th width="160">@sortablelink('created_at', __('Created At'))</th>
                                    <th width="160" class="text-center">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td class="text-center">
                                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                                        </td>
                                        <td>{{ $post->created_at }}</td>
                                        <td class="text-right table-options">
                                            @can('edit_year')
                                                <a class="table-action hover-primary cat-edit"
                                                href="{{ route('admin.year.edit', $post->id) }}" data-provide="tooltip"
                                                title="Edit"><i class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('delete_year')
                                                {!! Form::open(array('route' => array('admin.year.destroy', $post->id), 'method' => 'delete' , 
                                                    'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                                <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit" 
                                                    class="btn btn-pure table-action hover-danger confirmation-popup">
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
                        <div class="card-footer text-center">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection