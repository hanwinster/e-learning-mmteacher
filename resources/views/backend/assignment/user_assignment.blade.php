@extends('backend.layouts.default')

@section('title', __('User\'s Assignment'))

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Card Start -->
            <div class="card">
                <h4 class="card-title">
                    [Show] #<strong title="ID">{{ $assignment->id }}</strong>
                </h4>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 pt-4">
                            <h4>Assignment Title : {{$assignment->title}}</h4>
                            <div>
                                @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                                    <a href="{{asset($resource->getUrl())}}"  class=""> <i class="ti-clip"></i>{{ $resource->file_name }}</a>
                                @endforeach
                            </div>

                            <a href="{{route('admin.course.show', $assignment->course_id)}}#nav-assignment" class="pull-right">@lang('Go To Assignments')</a>
                            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>#No</th>
                                        <th>Assigned User</th>
                                        <th>Answer Attached File</th>
                                        <th style="width: 40%">Comment</th>
                                        <th>Comment By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user_assignments as $key => $assignment_user)
                                        <tr class="item_row{{$assignment_user->id}}">
                                            <td>{{$assignment_user->id}}</td>
                                            <td>{{$assignment_user->user->name}}</td>
                                            <td>
                                                @foreach($assignment_user->getMedia('user_assignment_attached_file') as $resource)
                                                    <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
                                                    ({{ $resource->human_readable_size }})
                                                @endforeach
                                            </td>
                                            <td>{{ $assignment_user->comment }}</td>
                                            <td>{{ $assignment_user->commentUser->name ?? '' }}</td>
                                            <td>
                                                @can('add_assignment_comment')
                                                <button class="edit-modal btn btn-primary" data-value="{{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}" data-id="{{$assignment_user->id}}" data-title="{{$assignment_user->assignment->title}}" data-comment="{{$assignment_user->comment}}">
                                                   {{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}
                                                </button>
                                                @endcan
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
                                    <input type="hidden" class="form-control" id="assignment_user_id" disabled>
                                <div class="form-group">
                                    <label class="control-label" for="comment">Comment:</label>
                                    <textarea  v-validate="'required|max:255'" name="comment" placeholder="Comment..." rows="10"  id="comment" class="form-control">{{old('comment', isset($assignment_user->comment) ? $assignment_user->comment: '')}}</textarea>
                              </div>
                              </form>
                              <div class="modal-footer">
                                <button type="button" class="btn actionBtn" data-dismiss="modal">
                                  <span id="footer_action_button" class='glyphicon'> </span>
                                </button>
                                <button type="button" class="btn btn-warning" data-dismiss="modal">
                                  <span class='glyphicon glyphicon-remove'></span> Close
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                </div>
                <footer class="card-footer text-center">
                        {{ $user_assignments->links() }}
                </footer>
            </div>
            <!-- END Card -->
        </div>
    </div>
</div>
@stop

@section('css')
@parent
@endsection

@section('js')
@parent
<script>
    $(document).ready(function() {
        // Edit Data (Modal and function edit data)
        $(document).on('click', '.edit-modal', function() {
            $('#footer_action_button').text(" Update");
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').addClass('edit');
            $('.modal-title').text($(this).data('value'));
            $('#assignment_user_id').val($(this).data('id'));
            $('#comment').val($(this).data('comment'));
            $('#myModal').modal('show');
        });

          $('.modal-footer').on('click', '.edit', function(e) {
              $.ajax({
                  type: 'POST',
                  url: "{{route('admin.assignment.review')}}",
                  dataType: 'text',
                  data: {
                      '_token': $('input[name=_token]').val(),
                      'id': $("#assignment_user_id").val(),
                      'comment': $('#comment').val()
                  },
                  success: function(data) {
                    let id = $("#assignment_user_id").val();
                      $('.item_row' + id).replaceWith(data);
                  }
              });
            });
    });
</script>

@endsection
