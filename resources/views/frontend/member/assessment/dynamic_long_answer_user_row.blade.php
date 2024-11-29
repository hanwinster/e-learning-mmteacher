<tr class="item_row{{$answer->id}}">
    <td>{{$answer->id}}</td>
    <td>{{$answer->user->name}}</td>
    <td>
        {{$answer->answers[0]}}
    </td>
    <td>{{$answer->pass_option}}</td>
    <td>{{$answer->comment}}</td>
    <td>{{$answer->commentUser->name}}</td>
    <td>
    	
    <button class="edit-assess-la-modal btn btn-primary" data-assesscomment="{{$answer->comment}}"
            data-id="{{$answer->id}}" data-assessstatus="{{$answer->pass_option}}" 
            data-value="{{ $answer->comment ? 'Edit Comment' : 'Create Comment' }}" >
        {{ $answer->comment ? 'Edit Feedback' : 'Create Feedback' }}
    </button>
       
    </td>
</tr>