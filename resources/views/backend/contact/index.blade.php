@extends('backend.layouts.default')

@section('title', __('Contact Messages'))

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
                        <li class="breadcrumb-item active">{{ __('Contact Messages') }}</li>
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
                                @lang('Contact Messages')
                            </h4>
                            <div class="card-tools text-right" style="width: 50%">
                                <form action="{{ route('admin.contact.index') }}" method="get">
                                    <input name="search" class="form-control top-input" 
                                        placeholder="Search" type="text" value="{{ request('search') }}">
                                    <button class="btn btn-primary btn-sm">{{ __('Search') }}</button>
                                    <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary btn-sm">
                                        {{ __('Reset') }}
                                    </a>                         
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                                <thead>
                                <tr>
                                    <th width="60">@sortablelink('id', __('ID'))</th>
                                    <th>@sortablelink('subject', __('Subject'))</th>
                                    <th>@sortablelink('name', __('name'))</th>
                                    <th>@sortablelink('region_state', __('State/Region'))</th>
                                    <th width="100">{{ __('Status') }}</th>
                                    <th width="160">@sortablelink('updated_at', __('Updated At'))</th>
                                    <th width="160" class="text-center">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->subject }}</td>
                                        <td>{{ $post->name }}</td>
                                        <td>{{ $post->getState() }}</td>
                                        <td>{{ $post->getStatus() }}</td>
                                        <td>{{ $post->updated_at->format('Y-m-d') }}</td>
                                        <td class="text-right table-options">
                                            @can('edit_contact')
                                            <a class="table-action hover-primary cat-edit"
                                                href="{{ route('admin.contact.edit', $post->id) }}" data-provide="tooltip"
                                                title="Show"><i class="fas fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('delete_contact')
                                                {!! Form::open(array('route' => array('admin.contact.destroy', $post->id), 
                                                    'method' => 'delete' , 'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
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
