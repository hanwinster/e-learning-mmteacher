@extends('backend.layouts.default')

@section('title', __('Registered Users'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.course.index') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.course.show', $course->id) }}">{{ strip_tags($course->title) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.course.show', $course->id).'#nav-zoom' }}">{{ __('Live Sessions') }}</a></li>
                        <li class="breadcrumb-item active">
                            {{ __('Registered Users') }}                         
                        </li>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('layouts.form_alert') 
                <div class="col-12 mx-auto">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5>{{ $session->topic }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                 
                                    <table class="table table-striped no-footer">
                                        <thead>
                                            <tr>
                                                <th>@lang('No.')</th>
                                                <th>@lang('User Name')</th>
                                                <th>@lang('User Email')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sessionUsers as $key => $su)
                                                <tr class="item_row">
                                                    <td>{{$key+1}}</td>
                                                    @php 
                                                        $userName = \App\Repositories\UserRepository::getUserNameById($su->user_id);
                                                        $userEmail = \App\Repositories\UserRepository::getUserEmailById($su->user_id);
                                                    @endphp
                                                    <td>{{ $userName }}</td>
                                                    <td>{{ $userEmail }}</td>
                                                </tr>
                                            @empty
                                                <tr class="item_row">
                                                    <td colspan="2" class="text-center">{{ __('No records.')}}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>     
                                </div>
                            </div>

                        
                    </div>
                    <footer class="card-footer">
                        <a href="{{ route('member.course.show', $course->id).'#nav-zoom' }}" class="btn btn-outline-dark btn-md">{{ __('Back') }}</a>
                    </footer>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
@parent
<script>
    $(document).ready(function() {
        
    });
</script>
@endsection