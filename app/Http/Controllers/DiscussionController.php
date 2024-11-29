<?php

namespace App\Http\Controllers;

//use App\Http\Requests\RequestDiscussion as RequestDiscussion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Discussion; //ChatRoom
use App\Models\DiscussionMessage; //ChatMessage;
use App\User;
use App\Events\Message;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionRepository;
use Carbon\Carbon;


class DiscussionController extends Controller
{
    public function __construct(DiscussionRepository $repository, CourseRepository $courseRepository)
    {// maybe we don't need this as only APIs will be here
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->middleware(function($request, $next)  {
            if(auth()->check()) {
                $this->currentUserType = auth()->user()->type;
            } else {
                $this->currentUserType = 'guest';
            }
            return $next($request);
        });
    }

    //return all chat discussions/rooms
    public function discussions(Request $request)  {
        return Discussion::all();
    }

    //return all chat messages of a chat room
    public function messages(Request $request, $roomId) {
        return ChatMessage::where('chat_room_id', $roomId)
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    //called when we want to create a new message
    public function newMessage( Request $request) { //, $roomId) {
        //print_r($request->all()); exit;
        $newMessage = new DiscussionMessage; 
        $newMessage->user_id = $request->user_id; //Auth::id();
        $newMessage->username = $request->username;
        $newMessage->discussion_id = $request->discussion_id; //$roomId;
        $newMessage->message =  $request->message; 
        $newMessage->save(); //print_r($newMessage); echo "saved"; exit;
        //echo "about to broadcast";
        //broadcast(new Message($newMessage->id, $newMessage->message, $newMessage->user_id, $newMessage->username, $newMessage->discussion_id )); //->toOthers(); // broadcast to other users, not to self
        //echo "\n before event"; 
        $user =  User::getUserById($newMessage->user_id);
        $thumb = $user->getThumbnailPath();
        $avatar = strpos($thumb, "/storage") ? env('APP_URL').$thumb : $thumb;
        event(new Message($newMessage->id, $newMessage->message, $newMessage->user_id, $newMessage->username, 
            $newMessage->discussion_id, $newMessage->created_at, $avatar ));
        return ["success" => true ]; //$newMessage;  

    }

    //called when we want to create a new message
    public function newMessageChatWindow( Request $request) { 
        //print_r(auth()->user()); exit;
        $newMessage = new DiscussionMessage; 
        $newMessage->user_id = $request->user_id; //Auth::id();
        $newMessage->username = $request->username;
        $newMessage->discussion_id = $request->discussion_id; //$roomId;
        $newMessage->message =  $request->message; 
        $newMessage->save(); //print_r($newMessage); echo "saved"; exit;
        //echo "about to broadcast";
        //broadcast(new Message($newMessage->id, $newMessage->message, $newMessage->user_id, $newMessage->username, $newMessage->discussion_id )); //->toOthers(); // broadcast to other users, not to self
        //echo "\n before event"; 
        $user =  User::getUserById($newMessage->user_id);
        $thumb = $user->getThumbnailPath();
        $avatar = strpos($thumb, "/storage") ? env('APP_URL').$thumb : $thumb;
        event(new Message($newMessage->id, $newMessage->message, $newMessage->user_id, $newMessage->username, 
            $newMessage->discussion_id, $newMessage->created_at, $avatar ));
        return ["success" => true ]; //$newMessage;  

    }

    public function getTest(Request $request) {
        return $request->all();
    }

    //return all chat messages of a chat room
    public function getMessages(Request $request) {
        return ChatMessage::where('chat_room_id', 1)
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

}