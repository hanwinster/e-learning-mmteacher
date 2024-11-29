@extends('backend.layouts.default')

@section('title', __('Notifications'))

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mx-auto">                 
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4>{{ __('Notifications') }}&nbsp;({{auth()->user()->notifications->count()}})</h4>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover">
                                @foreach($notifications as $notification)
                                    <tr class="bd-highlight hover-shadow-2">
                                        <td>
                                            <a class="text-muted" href="{{ route('member.notification.show', $notification->id) }}">
                                                @if ($notification->read_at == null)
                                                    <span class="text-primary">{{ $notification->data['title'] }}</span>
                                                @else
                                                    {{ strip_tags($notification->data['title']) }}
                                                @endif
                                            </a>
                                        </td>
                                        <td width="120">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </td>
                                        <td width="30">
                                            <a class="text-danger" onclick="return confirm('Are you sure you want to delete?')" href="{{ route('member.notification.destroy', $notification->id) }}"><i class="ti-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="card-footer">
                            @if ($notifications)
                            <div>
                                {{ $notifications->links() }}
                            </div>
                            @endif
                        </div>
                    </div>                                           
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
