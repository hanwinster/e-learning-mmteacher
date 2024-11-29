@extends('frontend.layouts.default')

@section('title', __('User Manuals'))

@section('header')
     
@endsection

@section('content')
<section class="page-section mt-6" >
    <div class="container-fluid">
        <div class="row pl-2"> 
            <div id="manual-side-bar" class="col-12 col-md-3 border-shadow-box p-3"> 
                @foreach($manuals as $key=>$manual)
                    <a href="{{  route('user-manuals', $key+1 )  }}" class="d-block pb-5 {{ $key+1 == $current['id'] ? 'fw-bold' : '' }}">
                        @lang($manual['title'])
                    </a>
                @endforeach
            </div>
            <div id="manual-main-content" class="col-12 col-md-9">               
                <div class="row">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('User Manuals') }}</li>
                        </ol>
                    </nav>
                </div>
                @include('frontend.layouts.form_alert')
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col-12 col-lg-10 manual-content-area">
                        <div class ="row">
                            <div class="col-12 col-md-12">
                                <h5 class="mt-2 mb-3 primary-color">
                                    @lang($current['title'])
                                </h5>
                            </div>
                        </div>                                             
                        <div class="panel">
                            <div class="embed-responsive embed-responsive-16by9 mt-4" id="vdo-wrapper">
                                @if($current)
                                <object data="{{ asset( $current['link'] )  }}" 
                                    type="application/pdf" width="100%" height="550">
                                    <p></p>
                                </object>  
                                @endif
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="col-12 col-lg-1"></div>
                </div>
                
            </div>         
        </div>      
    </div>
</div>
@endsection