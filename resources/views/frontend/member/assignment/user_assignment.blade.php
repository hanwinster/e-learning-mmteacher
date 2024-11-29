@extends('backend.layouts.default')

@section('title', __('User\'s Assignment'))

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', [$assignment->question->quiz->course_id] )}}#nav-assignment">
                            {{ strip_tags($assignment->question->title) }}</a>
                        </li>
                        <li class="breadcrumb-item active"> 
                            {{ __('Submitted Assignments') }}                          
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
                            <h5>{{ __('Manage Submitted Assignment(s) of') }}&nbsp;
                                {{ strip_tags($assignment->question->title) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="pt-1 pb-3">{{ __('Uploaded File(s)') }} - 
                                        @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                                            <a href="{{asset($resource->getUrl())}}" target="_blank"  class=""> <i class="ti-clip"></i>{{ $resource->file_name }}</a>
                                        @endforeach
                                    </div>
                                    
                                    <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#@lang('No')</th>
                                                <th>@lang('Assigned User')</th>
                                                <th>@lang('Answer Attached File')</th>
                                                
                                                <th>@lang('Score')</th>
                                                <th style="width: 40%">@lang('Comment')</th>
                                                <th>@lang('Commented By')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($user_assignments as $key => $assignment_user)
                                                <tr class="item_row{{$assignment_user->id}}">
                                                    <td>{{$assignment_user->id}}</td>
                                                    <td>{{$assignment_user->user->name}}</td>
                                                    <td>
                                                        @foreach($assignment_user->getMedia('user_assignment_attached_file') as $resource)
                                                            <a href="{{asset($resource->getUrl())}}" target="_blank" 
                                                                class="">{{ $resource->file_name }}</a>
                                                            ({{ $resource->human_readable_size }})
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $assignment_user->score }}</td>
                                                    <td>{{ $assignment_user->comment }}</td>
                                                    <td>{{ $assignment_user->commentUser->name ?? '' }}</td>
                                                    <td>
                                                        @php
                                                            $canReviewAssignment = App\Repositories\AssignmentRepository::canReview($assignment);
                                                        @endphp
                                                        @if($canReviewAssignment)
                                                        <button class="edit-modal btn btn-primary" data-comment="{{$assignment_user->comment}}"
                                                            data-id="{{$assignment_user->id}}" data-title="{{$assignment_user->assignment->title}}" 
                                                            data-value="{{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}" >
                                                           {{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>     
                                </div>
                            </div>

                        <!------------------- Modal Start -------------------------->
                          <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form">
                                        @csrf
                                        <input type="hidden" class="form-control" id="assignment_user_id">
                                        <div class="form-group">
                                            <label class="control-label" for="comment">@lang('Score')<span class="required">*</span></label>
                                            <input type="number"  v-validate="'required'" name="score" placeholder="@lang('Score')" id="score"  
                                                class="form-control" value="{{old('score', isset($assignment_user->score) ? $assignment_user->score: '')}}" />
                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="comment">@lang('Comment')<span class="required">*</span></label>
                                            <textarea  v-validate="'required|max:255'" name="comment" placeholder="Comment..." rows="10"  id="comment"  
                                                class="form-control">{{old('comment', isset($assignment_user->comment) ? $assignment_user->comment: '')}}
                                            </textarea>
                                        </div>
                                    </form>
                                    <div class="deleteContent">
                                        @lang('Are you Sure you want to delete?') <span class="title"></span> ?
                                    <span class="hidden id"></span>
                                  </div>
                                  <div class="modal-footer user-assignment">
                                    <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">
                                      <span id="footer_action_button" class='glyphicon'> </span>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal">
                                      @lang('Close')
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        <!------------------- Modal End --------------------------> 

                    </div>
                    <footer class="card-footer text-center">
                        {{ $user_assignments->links() }}
                    </footer>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
