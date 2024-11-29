@extends('backend.layouts.default')

@section('title', __('Pages'))

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
                        <li class="breadcrumb-item active">{{ __('Pages') }}</li>
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
                                @can('add_page')
                                    <a href="{{ route('admin.page.create') }}" class="btn btn-primary btn-md">@lang('New')</a>
                                @endcan
                            </h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.page.index') }}" method="get">
                                    <input name="search" placeholder="Search" type="text" class="form-control top-input" value="{{ request('search') }}">
                                    <button class="btn btn-primary btn-md">{{__('Search') }}</button>
                                    <a href="{{ route('admin.page.index') }}" class="btn btn-secondary btn-md">{{__('Reset') }}</a>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered no-footer">
                                <thead>
                                <tr>
                                    <th width="60">@sortablelink('id', 'ID')</th>
                                    <th>@sortablelink('title',__('Title') )</th>
                                    <th>{{__('Slug') }}</th>
                                    <th width="100">{{__('Published') }}</th>
                                    <th width="160">@sortablelink('updated_at', __('Updated At') )</th>
                                    <th width="160" class="text-center">{{__('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ strip_tags($post->title) }}
                                            @if($post->title_mm)
                                              &nbsp;({{ strip_tags($post->title_mm) }})  
                                            @endif
                                        </td>
                                        <td>{{ $post->slug }}</td>
                                        <td class="text-center">
                                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                                        </td>
                                        <td>{{ $post->updated_at }}</td>
                                        <td class="text-right table-options">
                                            @php 
                                                switch($post->slug) {
                                                    case 'about-us': $previewPath = route('home')."#about";break;
                                                    case 'contact-us': $previewPath = route('home')."#contact";break;
                                                    case 'disclaimer': $previewPath = route('home')."#disclaimer";break;
                                                    default: $previewPath = url($post->path());break;
                                                }
                                            @endphp
                                            <a target="_blank" class="table-action hover-primary cat-edit mr-3" href="{{$previewPath}}" 
                                                data-provide="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit_page')
                                                <a class="table-action hover-primary cat-edit"
                                                href="{{ route('admin.page.edit', $post->id) }}" data-provide="tooltip" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('delete_page')
                                                {!! Form::open(array('route' => array('admin.page.destroy', $post->id), 'method' => 'delete' , 'onsubmit'
                                                => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
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
