@extends('backend.layouts.default')

@section('title', 'Articles')

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
                                @can('add_article')
                                    <a href="{{ route('admin.article.create') }}" class="btn btn-primary btn-md">{{ __('New') }}</a>
                                @endcan
                            </h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.article.index') }}" method="get">
                                    <input name="search" placeholder="Search" class="form-control top-input" type="text" value="{{ request('search') }}">
                                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control top-select']) !!}
                                    <button class="btn btn-primary btn-md">{{ __('Search') }}</button>
                                    <a href="{{ route('admin.article.index') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="60">@sortablelink('id', 'ID')</th>
                                    <th>@sortablelink('title', 'Title')</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>@sortablelink('category_id', 'Category')</th>
                                    <th>{{ __('Published') }}</th>
                                    <th width="150">@sortablelink('updated_at', 'Updated At')</th>
                                    <th width="150" class="text-center">{{__('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                    function UR_exists($url){
                                        $headers=get_headers($url);
                                        return stripos($headers[0],"200 OK")?true:false;
                                        }
                                    @endphp
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>
                                            
                                            @if(!file_exists(public_path($post->getThumbnailPath())))
                                                @foreach($post->media as $mediafile)
                                                    @php
                                                    $_filename = str_replace('-thumb','',$mediafile->file_name);
                                                    @endphp
                                                    <img src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" alt="{{ $post->title }}" width="150px">
                                                    @break
                                                @endforeach
                                            
                                            @elseif ($img_url = $post->getThumbnailPath())
                                                <img src="{{ asset($img_url) }}" alt="{{ $post->title }}">
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ $post->category->title ?? '' }}</td>
                                        <td class="text-center">
                                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                                        </td>
                                        <td>{{ $post->updated_at }}</td>
                                        <td class="text-right table-options">
                                            <!-- COMMENTED FOR NO ARTICLES DISPLAYED IN FE -->
                                            <!-- <a target="_blank" class="table-action hover-primary cat-edit mr-3" href="{{ url($post->path()) }}" 
                                                data-provide="tooltip" title="View"><i class="fas fa-eye"></i></a> -->

                                            @can('edit_article')
                                                <a class="table-action hover-primary cat-edit"
                                                href="{{ route('admin.article.edit', $post->id) }}" data-provide="tooltip"
                                                title="Edit"><i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete_article')
                                                {!! Form::open(array('route' => array('admin.article.destroy', $post->id), 'method'
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
                        <div class="card-footer text-center">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
