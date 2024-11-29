@extends('backend.layouts.default')

@section('title', 'Dashboard')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li> -->
                        <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (auth()->user()->isAdmin()) 
                @include('frontend.member.dashboard.partials.admin-dashboard')  
            @elseif (auth()->user()->isManager() && auth()->user()->isUnescoManager()) 
                @include('frontend.member.dashboard.partials.unesco-mgr-dashboard')           
            @elseif (  !auth()->user()->isUnescoManager() && (auth()->user()->isManager() || auth()->user()->isTeacherEducator() ) )
                @include('frontend.member.dashboard.partials.manager-teacher-dashboard')
            @else 
                @include('frontend.member.dashboard.partials.student-dashboard')       
                <!-- ( auth()->user()->isStudentTeacher() || auth()->user()->isJournalist() 
                    || auth()->user()->isIndependentLearner() )  -->      
            @endif
        </div>
    </section>
</div>
@endsection

@section('script')
@parent
<script type="text/javascript">
    $(document).ready(function() { 

        
            
    });
</script>
@endsection