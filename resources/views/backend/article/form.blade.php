@extends('backend.layouts.default')

@section('title', __('Article'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.article.index') }}">{{ __('Articles') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))
                                {{ __('Edit Article') }}
                            @else 
                                {{ __('Add Article') }}
                            @endif
                        </li>
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
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (isset($post))
                                {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.article.update', $post->id)
                                , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => true, 'route' => 'admin.article.store',
                                'class' => 'form-horizontal')) !!}
                            @endif

                            <div class="form-group">
                                <label for="title" >@lang('Title')&nbsp;<span class="required">*</span></label>
                                <input type="text" placeholder="{{ __('Title') }}" name="title" id="title"
                                    v-validate="'required'" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                       value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
    
                            <div class="form-group">
                                <label for="title_mm">@lang('Title in Myanmar')</label>
                                <input type="text" placeholder="{{ __('Title in Myanmar') }}" name="title_mm" id="title_mm"
                                       class="form-control{{ $errors->has('title_mm') ? ' is-invalid' : '' }}"
                                       value="{{ old('title_mm', isset($post->title_mm) ? $post->title_mm: '') }}">
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="category_id" class="col-xs-12 require">
                                    @lang('Category')
                                </label>
                                {!! Form::select('category_id', $categories, old('category_id', isset($post->category_id)
                                ? $post->category_id: ''), ['class' => $errors->has('category_id') ? 'form-control is-invalid' : 'form-control']) !!}
                                {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="body" >@lang('Body')&nbsp;<span class="required">*</span></label>
                                <textarea v-validate="'required'" data-height="200" class="form-control summernote {{ $errors->has('body') ? ' is-invalid' : '' }}" 
                                    rows="5" name="body" id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                                {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="body" >@lang('Body in Myanmar')</label>
                                <textarea data-height="200" class="form-control summernote {{ $errors->has('body_mm') ? ' is-invalid' : '' }}" 
                                    rows="5" name="body_mm" id="body">{{ old('body_mm', isset($post->body_mm) ? $post->body_mm: '') }}</textarea>
                                {!! $errors->first('body_mm', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="hyperlink" >
                                    @lang('Link for Learn More Button')
                                    &nbsp;<span class="required">*</span>
                                </label>
                                <input type="text" placeholder="{{ __('URL Link') }}" name="hyperlink" id="hyperlink"
                                    v-validate="'required'" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                       value="{{ old('hyperlink', isset($post->hyperlink) ? $post->hyperlink: '') }}">
                                {!! $errors->first('hyperlink', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    @php
                                        $images = isset($post) ? $post->getMedia('articles') : [];
                                    @endphp
                                    <label for="uploaded_file">
                                        @lang('Image')&nbsp;
                                        @if (!isset($post) || (isset($post) && !$images))
                                            <span class="required">*</span> 
                                        @endif
                                    </label>
                                    {{ Form::file('uploaded_file') }}
                                    @if( isset($post) )                                   
                                        <div>
                                            @foreach($images as $image)
                                                @php
                                                //$_filename = str_replace('-thumb','',$post->media[0]->file_name);  
                                                //dd($image->toArray());
                                                @endphp
                                                <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                    {{-- <img src="{{ asset($image->getUrl('thumb')) }}"> --}}
                                            
                                                    @if(!file_exists(public_path($post->getThumbnailPath())))
                                                        <img src="{{ asset('storage/'.$image->id.'/'.$image->file_name) }}" alt="{{ $post->title }}" width="150px">                                        
                                                    @else
                                                        <img src="{{ asset($image->getUrl('thumb')) }}">
                                                    @endif
                                                </a>
                                                <a onclick="return confirm('Are you sure you want to delete?')" href="{{ route('admin.media.destroy', $image->id) }}">@lang('Remove')</a>
                                            @endforeach
                                        </div>
                                    @endif
                                    {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                                    @if($errors->any())
                                        @php foreach($errors->all() as $err) {
                                                if($err == "The uploaded file field is required.") {
                                                    echo "<div class='required'>". $err ."</div>";
                                                }
                                            }
                                        @endphp
                                        {{-- <div class="required">{{ implode('', $errors->all(':message')) }} </div> --}}          
                                    @endif
                                </div>
                            </div>

                            @can('publish_article')
                                <div class="form-group">
                                    <div>
                                        <label for="published_yes" class="col-xs-12">@lang('Published')</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), 
                                            ['id' => 'published_yes', 'class' => 'form-check-input']) }}
                                        <label for="published_yes" class="form-check-label">@lang('Yes')</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), 
                                            ['id' => 'published_no', 'class' => 'form-check-input']) }}
                                        <label for="published_no" class="form-check-label">@lang('No')</label>
                                    </div>
                                    {!! $errors->first('published', '<p class="help-block">:message</p>') !!}
                                </div>
                            @endcan

                            <div class="form-group">
                                @if (auth()->user()->can('add_article') || auth()->user()->can('edit_article'))
                                    <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">@lang('Save')</button>
                                @endif
                                <a href="{{ route('admin.article.index') }}" class="btn btn-outline-dark btn-sm ">@lang('Cancel')</a>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection