@extends('backend.layouts.default')

@section('title', __('Users'))

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
                        <li class="breadcrumb-item active">{{ __('Users') }}</li>
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
                            <h4 class="card-title mb-2">
                                @lang('Users')&nbsp;
                                @can('add_user')
                                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>
                                @endcan       
                            </h4>
                            <div class="card-tools">

                                <form action="{{ route('admin.user.index') }}" method="get">
                
                                    <input name="search" placeholder="Search" class="form-control top-input" 
                                        type="text" value="{{ request('search') }}">
                                    {!! Form::select('type', $accessible_rights, request('type', ''), 
                                        ['class' => 'form-control top-select']) !!}
                                    {!! Form::select('ec_college', $ec_colleges, request('ec_college', ''), 
                                        ['placeholder' => '-Education College-', 'class' => 'form-control top-select']) !!}
                                    {!! Form::select('role_name', $roles, request('role_name', ''), 
                                        ['placeholder' => '-Role-', 'class' => 'form-control top-select']) !!}
                                    {!! Form::select('verified', $yes_no, request('verified', ''), 
                                        ['placeholder' => '-Verified-', 'class' => 'form-control top-select']) !!}
                                    {!! Form::select('approved', $approvalStatus, request('approved'), 
                                        ['class' => 'form-control top-select', 'placeholder' => '-Select Status-' ]) !!}

                                    <button class="btn btn-primary btn-md mt-2">{{__('Search') }}</button>
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-md mt-2">{{ __('Reset') }}</a>                
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="60">@sortablelink('id', 'ID')</th>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th>@sortablelink('type', 'Accessible Right')</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Education College') }}</th>
                                        <th>{{ __('Verified') }}</th>
                                        <th>{{ __('Approved') }}</th>
                                        <th>@sortablelink('created_at', 'Created At')</th>
                                        <th width="140" class="text-center">{{__('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr class="{{ $post->approved == App\User::APPROVAL_STATUS_BLOCKED ? 'bg-pale-pink' : '' }}">
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->name }}
                                            <br>
                                            <span class="text-cyan">{{ $post->email }}</span>
                                        </td>
                                        <td>{{ $post->type }}</td>
                                        <td>
                                            @foreach($roles as $idx => $role)
                                                @if($post->role_id == $idx)
                                                    {{ $role }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $post->college->title ?? '' }}
                                            @if ($post->user_type)
                                            <div>({{ $post->getStaffType() }})</div>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            {{ $post->verified == 1 ? __('Yes'): __('No') }}
                                        </td>
                                        <td class="text-left">
                                            {{ $post->getApprovalStatus($post->approved) }}
                                        </td>
                                        <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                       
                                        <td class="text-center">
                                            @if ($post->id != 1)
                                                @can('edit_user')
                                                    <a class="table-action hover-primary cat-edit"
                                                        href="{{ route('admin.user.edit', $post->id) }}"
                                                        data-provide="tooltip" title="Edit">
                                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                            title="@lang('Edit')"><i class="fas fa-edit"></i></span>
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($post->id != 1)
                                                @can('delete_user') 
                                                    {!! Form::open(array('route' => array('admin.user.destroy', $post->id), 'method' => 'delete' ,
                                                    'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display:
                                                    inline', '')) !!}
                                                    <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit"
                                                        class="btn btn-pure table-action confirmation-popup">
                                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                            title="@lang('Delete')"><i class="fas fa-trash"></i></span>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endcan 
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-center">
                            {{ $posts->links() }}
                            <!-- <p>
                                {{ __('Legend') }}: <span class="bg-pale-pink"
                                    style="display: inline-block; width: 50px; height: 50">&nbsp;</span> {{ __('User is blocked.')}}
                            </p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
