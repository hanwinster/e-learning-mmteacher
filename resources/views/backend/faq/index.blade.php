@extends('backend.layouts.default')

@section('title', __('FAQs'))

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
                        <li class="breadcrumb-item active">{{ __('FAQs') }}</li>
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
                                @can('add_faq')
                                    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary btn-md">{{ __('New') }}</a>
                                @endcan
                            </h4>
                            <form action="{{ route('admin.faq.index') }}" method="get">
                                <div class="row">                            
                                    <div class="col-12 col-md-4">
                                        <input name="search" placeholder="{{__('Search') }}" class="form-control" 
                                        type="text" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <button class="btn btn-primary btn-md">{{ __('Search') }}</button>
                                        <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>    
                                    </div>                                                         
                                </div>
                            </form>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                                <thead>
                                <tr>
                                    <th width="60">@sortablelink('id', __('ID'))</th>
                                    <th>@sortablelink('question', __('Question'))</th>
                                    <th>@sortablelink('answer', __('Answer'))</th>
                                    <th>@sortablelink('category_id', __('Category'))</th>
                                    <th>{{ __('Published') }}</th>
                                    <th width="150">@sortablelink('updated_at', __('Updated At'))</th>
                                    <th width="150" class="text-center">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ strip_tags($post->question) }}</td>
                                        <td>{{ strip_tags($post->answer) }}</td>
                                        <td>{{ $post->category->title ?? '' }}</td>
                                        <td class="text-center">
                                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                                        </td>
                                        <td>{{ $post->updated_at }}</td>
                                        <td class="text-right table-options">
                                            @can('edit_faq')
                                                <a class="table-action hover-primary cat-edit"
                                                href="{{ route('admin.faq.edit', $post->id) }}" data-provide="tooltip"
                                                title="Edit"><i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete_faq')
                                                {!! Form::open(array('route' => array('admin.faq.destroy', $post->id), 'method'
                                                => 'delete' , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
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
