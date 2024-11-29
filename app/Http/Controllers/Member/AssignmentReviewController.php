<?php

namespace App\Http\Controllers\Member;

use App\Notifications\FeedbackAssignment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssignmentUser;
use App\Models\Assignment;
use Illuminate\Support\Facades\Notification;

class AssignmentReviewController extends Controller
{
    public function reviewAssignment(Request $request)
    {
        $assignment_user = AssignmentUser::findOrFail($request->id);
        $assignment = Assignment::findOrFail($assignment_user->assignment_id);
        if (empty($assignment_user->comment_by)) {
            $assignment_user->comment_by = auth()->user()->id; 
        }
        $assignment_user->comment = $request->comment;
        $assignment_user->score =$request->score ? $request->score : 0;
        $assignment_user->save();

        Notification::send(User::query()->where('id', $assignment_user->user_id)->first(), new FeedbackAssignment($assignment_user));

        return view('frontend.member.assignment.dynamic_assignment_user_row', compact('assignment', 'assignment_user'));
    }
}
