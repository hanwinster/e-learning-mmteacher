<tr class="item_row{{$assignment_user->id}}">
    <td>{{$assignment_user->id}}</td>
    <td>{{$assignment_user->user->name}}</td>
    <td>
    	@foreach($assignment_user->getMedia('user_assignment_attached_file') as $resource)
            <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
            ({{ $resource->human_readable_size }})
        @endforeach
    </td>
    <td>{{$assignment_user->comment}}</td>
    <td>{{$assignment_user->commentUser->name}}</td>
    <td>
    	@php
            $canReviewAssignment = App\Repositories\AssignmentRepository::canReview($assignment);
        @endphp
        @if($canReviewAssignment)
        <button class="edit-modal btn btn-primary" data-value="{{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}" data-id="{{$assignment_user->id}}" data-title="{{$assignment_user->assignment->title}}" data-comment="{{$assignment_user->comment}}">
           {{ $assignment_user->comment ? 'Edit Comment' : 'Create Comment' }}
        </button>
        @endif
    </td>
</tr>