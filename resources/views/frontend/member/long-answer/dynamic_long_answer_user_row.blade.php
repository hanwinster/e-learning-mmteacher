<tr class="item_row {{$answer->id}}">
    <td>{{$answer->id}}</td>
    <td>{{$answer->user->name}}</td>
    <td>
        {{$answer->submitted_answer[0]}}
    </td>
    <td>{{$answer->status}}</td>
    <td>{{$answer->comment}}</td>
    <td>{{$answer->commentUser->name}}</td>
    <td>
    	
    <button class="edit-modal btn btn-primary" data-comment="{{$answer->comment}}"
                                                                data-id="{{$answer->id}}" data-status="{{$answer->status}}" 
                                                                data-value="{{ $answer->comment ? 'Edit Comment' : 'Create Comment' }}" >
                                                                {{ $answer->comment ? 'Edit Feedback' : 'Create Feedback' }}
                                                            </button>
       
    </td>
</tr>