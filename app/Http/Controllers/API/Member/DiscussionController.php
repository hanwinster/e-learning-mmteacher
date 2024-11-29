<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\User;
use App\Models\Discussion; //ChatRoom
use App\Models\DiscussionMessage; //ChatMessage;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\DiscussionRepository;
/* TODO: to check if we need to trigger the event for new message or not */
class DiscussionController extends Controller
{
    protected $clRepo;

    public function __construct(CourseLearnerRepository $clRepository, DiscussionRepository $repository)
    {   
        $this->clRepo = $clRepository;
        $this->repository = $repository;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $courseId)
    {
        $user = auth()->user();
        $discussion = Discussion::where('course_id', $courseId)->first();
        if(!$discussion) {
            return response(['errors' => 'No Discussion Found!' ], 404);  
        }
        
        foreach($discussion->discussionMessages as $idx => $data) {
            $discussion->discussionMessages[$idx]['avatar'] = isset($discussion->discussionMessages[$idx]->user) && $discussion->discussionMessages[$idx]->user->getThumbnailPath() ? 
                            env('APP_URL').$discussion->discussionMessages[$idx]->user->getThumbnailPath() : env('APP_URL')."/assets/img/avatar.png";
        }
        $json = [
            'data' => $discussion->discussionMessages            
        ];


        return response()->json($json,200);
    }

    public function addMessage( Request $request, $courseId, $roomId) 
    { 
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $discussion = Discussion::where('id', $roomId)->where('course_id',$courseId)->first();
        if(!$discussion) {
            return response()->json(['code' => 404, 'message' => 'Discussion/chat room is not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        if(!$course->allow_discussion) {
            return response()->json(['code' => 403, 'message' => 'Discussion is not available for this course'], 403);
        }
        if(!$discussion->allow_learners) { // if allowed for all learners, no need to check if it's taken or not
            $canParticipateInChat = CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course);
            if(!$canParticipateInChat) {
                return response()->json(['code' => 403, 'message' => 'You need to take the course first to participate in the discussions'], 403);
            }
        }
        
        $newMessage = new DiscussionMessage; 
        $newMessage->user_id = auth()->user()->id;
        $newMessage->username = auth()->user()->username;
        $newMessage->discussion_id = $roomId; //$roomId;
        $newMessage->message =  $request->message; 
        $newMessage->save(); //print_r($newMessage); echo "saved"; exit; 
        $user =  User::getUserById($newMessage->user_id);
        $thumb = $user->getThumbnailPath();
        $avatar = strpos($thumb, "/storage") ? env('APP_URL').$thumb : $thumb;
        
        return response()->json(["message" => "success", 
            "data" => [
            'new_message' => $newMessage, 
            'user_avator' => $avatar,
            ]
        ] ); 

    }
}
