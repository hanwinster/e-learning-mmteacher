@extends('backend.layouts.default')
@section('title', __('Course Approval Request'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.course.index')}}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item active">
                           {{ __('Course Approval Request') }}                        
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row gap-y">
                <div class="col-12 mx-auto">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{ __('Submit Course Approval Request') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (isset($post)) 
                                {!! \Form::open(array( 'method' => 'post', 
                                'route' => array('member.course.save-submit-request',
                                $post->id) , 'class' => 'form-horizontal')) !!} 
                            @endif
                                <div class="row">
                                    <div class="col-12 col-lg-8">
                                        <div class="form-group">
                                            <label for="description" class="require">{{ __('Description') }}</label>
                                            <textarea type="text" placeholder="Description.." name="description" id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description') }}</textarea>                                {!! $errors->first('description', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group">                                                 
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary" type="submit" name="btnSave" value="1">
                                                    {{ __('Submit') }}
                                                </button>
                                            </div>
                                            <a href="{{ route('member.course.index') }}" class="btn btn-sm btn-outline-dark">{{ __('Cancel') }}</a>
                                        </div>                                                
                                    </div>
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
