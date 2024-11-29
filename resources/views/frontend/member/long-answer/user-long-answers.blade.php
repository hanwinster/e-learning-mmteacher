@extends('backend.layouts.default')

@section('title', __('User\'s Long Answers'))

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
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', [$courseId] )}}#nav-quiz">
                            {{ strip_tags($long_answer->question->title) }}</a>
                        </li>
                        <li class="breadcrumb-item active"> 
                            {{ __('Submitted Answers') }}                          
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
                            <h5>{{ __('Manage Submitted Answer(s) of') }}&nbsp;
                                {{ strip_tags($long_answer->question->title) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="pt-1 pb-3">{{ __('Answer(s)') }} - 
                                       
                                    </div>
                                    
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#@lang('No')</th>
                                                <th>@lang('Submitted By')</th>
                                                <th style="width: 30%">@lang('Answer')</th>
                                                
                                                <th>@lang('Status')</th>
                                                <th style="width: 20%">@lang('Comment')</th>
                                                <th>@lang('Commented By')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($user_answers as $key => $answer)
                                                <tr class="item_row{{$answer->id}}">
                                                    <td>{{$answer->id}}</td>
                                                    <td>{{$answer->user->name}}</td>
                                                    <td>
                                                        {{$answer->submitted_answer[0]}}
                                                    </td>
                                                    <td>{{ $answer->status }}</td>
                                                    <td>{{ $answer->comment }}</td>
                                                    <td>{{ $answer->commentUser->name ?? '' }}</td>
                                                    <td>
                                                        
                                                        @if($canComment)
                                                            <button class="edit-modal btn btn-primary" data-comment="{{$answer->comment}}"
                                                                data-id="{{$answer->id}}" data-status="{{$answer->status}}" 
                                                                data-value="{{ $answer->comment ? 'Edit Comment' : 'Create Comment' }}" >
                                                                {{ $answer->comment ? 'Edit Feedback' : 'Create Feedback' }}
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
                                        <input type="hidden" class="form-control" id="answer_id" value="{{$answer->id}}">
                                        <div class="form-group">
                                            <label class="control-label" for="comment">@lang('Status')<span class="required">*</span></label>
                                            <!-- <input type="text"  v-validate="'required'" name="status" id="status"  
                                                class="form-control" value="{{old('score', isset($answer->score) ? $answer->score: '')}}" /> -->
                                            <select class="form-control" id="status" class="form-control" name="status"> 
                                                <option value="submitted">{{__('Submitted')}}</option>
                                                <option value="pass">{{__('Pass')}}</option>
                                                <option value="retake">{{__('Retake')}}</option>                          
                                            </select> 
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label" for="comment">@lang('Comment')<span class="required">*</span></label>
                                            <textarea  v-validate="'required|max:255'" name="comment" placeholder="Comment..." rows="10"  id="comment"  
                                                class="form-control">{{old('comment', isset($answer->comment) ? $answer->comment: '')}}
                                            </textarea>
                                        </div>
                                    </form>
                                    <div class="deleteContent">
                                        @lang('Are you Sure you want to delete?') <span class="title"></span> ?
                                    <span class="hidden id"></span>
                                  </div>
                                  <div class="modal-footer user-la-feedback">
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
                       
                    </footer>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
