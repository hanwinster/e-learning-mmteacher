@extends('frontend.layouts.default')
@section('title', __('Manage Articles'))

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-2">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-10 mx-auto">
                    <h1>{{ __('Manage Articles') }}</h1>
                    <div class="card">
                        <header class="card-header">
                            <h4 class="card-title">
{{--                                 <div class="btn-group-vertical" role="group">
                                    <button id="btnNewResource" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"  aria-expanded="false"> {{ __('New') }}  </button>

                                    <div class="dropdown-menu" aria-labelledby="btnNewResource">
                                        @foreach($formats as $key => $value)
                                            <a class="dropdown-item" href="{{ route('member.article.create-with-format', $key) }}">{{ title_case($value) }}</a>
                                        @endforeach
                                    </div>
                                </div> --}}
                                <a href="{{ route('member.article.create') }}" class="btn btn-primary">{{ __('New Article') }}</a>
                            </h4>

                            <form action="{{ route('member.article.index') }}" method="get">
                                <div class="card-header-actions row">
                                    <div class="col-md-3">
                                        <div class="lookup lookup-right d-none d-lg-block">
                                            <input name="search" class="form-control" placeholder="Search" type="text" value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                    {!! Form::select('category_id', $categories, request('category_id'), ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary">{{ __('Search') }}</button>
                                        <a href="{{ route('member.article.index') }}" class="btn">{{ __('Reset') }}</a>
                                    </div>

                                </div>
                            </form>
                        </header>

                        <div class="card-body">
                            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th width="80">@sortablelink('id', __('ID'))</th>
                                        <th>@sortablelink('title', __('Title'))</th>
                                        <th>@sortablelink('category_id', __('Category'))</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Published') }}</th>
                                        <th>{{ __('Uploaded By') }}</th>
                                        <th width="100px" class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            <a href="{{ route('article.show', $post->slug) }}" data-provide="tooltip"
                                                title="View">
                                            {{ $post->title }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $post->category->title ?? '' }}
                                        </td>
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
                                        <td class="text-center">
                                            {!! $post->published ? __('Yes'): __('No') !!}
                                        </td>
                                        <td>
                                            @if( isset($post->user->name))
                                            <a href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->name ?? '' }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-small">
                                                @php
                                                $canEdit = App\Repositories\ResourcePermissionRepository::canEdit($post);
                                                @endphp
                                                    @if ($canEdit)
                                                    <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.article.edit', $post->id) }}" data-provide="tooltip"
                                                        title="Edit"><i class="ti-pencil"></i> {{ __('Edit') }} </a>
                                                    @endif
                                                <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($canEdit)

                                                    {!! Form::open(array('route' => array('member.article.destroy', $post->id), 'method' => 'delete'
                                                    , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                                    <button data-provide="tooltip" style="cursor: grab" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                                    <i class="ti-trash"></i>
                                                    {{ __('Delete') }}
                                                    </button>
                                                    {!! Form::close() !!}
                                                    @endif
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
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
