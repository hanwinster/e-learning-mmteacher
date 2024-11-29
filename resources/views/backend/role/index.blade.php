@extends('backend.layouts.default')

@section('title', __('Roles'))

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
                        <li class="breadcrumb-item active">{{ __('Roles') }}</li>
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
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                @can('add_role')
                                    <a href="{{ route('admin.role.create') }}" class="btn btn-primary btn-md">{{ __('New Role') }}</a>&nbsp;
                                    <!-- <a href="{{ route('admin.role.create') }}" class="btn btn-primary btn-md">{{ __('New Permission') }}</a> -->
                                @endcan
                            </h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.role.index') }}" method="get">                              
                                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                                    <button class="btn btn-primary btn-md">{{ __('Search') }}</button>
                                    <a href="{{ route('admin.role.index') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>                                  
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered no-footer">
                                <thead>
                                    <tr>
                                        <th width="60">@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th width="160">@sortablelink('updated_at', 'Updated At')</th>
                                        <th width="160" class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->name }}</td>
                                        <td>{{ $post->updated_at }}</td>
                                        <td class="text-right table-options">
                                            @if ($post->id != 1)
                                                @can('edit_role')
                                                    <a class="table-action hover-primary cat-edit" 
                                                        href="{{ route('admin.role.edit', $post->id) }}" 
                                                        data-provide="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i></a>
                                                @endcan
                                            @endif

                                            @if ($post->id != 1)
                                                @can('delete_role')
                                                    {!! Form::open(array('route' => array('admin.role.destroy', $post->id), 
                                                        'method' => 'delete' , 'onsubmit' => 'return confirm("Are you sure you want to delete?");',
                                                    'style' => 'display: inline', '')) !!}
                                                        <button data-provide="tooltip" data-toggle="tooltip" title="Delete" 
                                                        type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
                                                        <i class="fas fa-trash"></i>
                                                        </button>
                                                    {!! Form::close() !!}
                                                @endcan
                                            @endif
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