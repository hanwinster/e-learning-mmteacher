<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Traits\ZoomJWT;
//use Illuminate\Http\Request;
use App\Http\Requests\RequestLiveSession as RequestLiveSession;
use Illuminate\Support\Facades\Validator;
use App\Models\Lecture;
use App\Models\LiveSession;
use App\Models\LiveSessionUser;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\LiveSessionRepository;

class LiveSessionController extends Controller
{
    use ZoomJWT;

    private $repository;
    private $clRepo;
    private $courseRepository;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function __construct(LiveSessionRepository $repository, CourseRepository $courseRepository,
    CourseLearnerRepository $clRepo) {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->clRepo = $clRepo;
    }

    public function list(\Illuminate\Http\Request $request) // list all the meetings created for an admin account
    {
        $path = 'users/me/meetings';
        $response = $this->zoomGet($path); 

        $data = json_decode($response->body(), true);
        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
            return $m;
        }, $data['meetings']);

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {   
        $course = $this->courseRepository->find($course_id);
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.live-session.form', compact('course','lectures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewRegistration($id)
    { 
        $session = $this->repository->find($id);
        $course = $this->courseRepository->find($session->course_id);
        $sessionUsers = LiveSessionUser::where('session_id',$id)->get();
        
        //dd($sessionUsers);exit;
        return view('frontend.member.live-session.user_live-session', compact('course', 'session', 'sessionUsers' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $post = $this->repository->find($id); 
        $course = $this->courseRepository->find($post->course_id);
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.live-session.form', compact('course', 'post','lectures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestLiveSession  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestLiveSession $request, $courseId)
    {        
        $validatedArr = $request->validated();
        if($validatedArr) {           
            $startDateArray = explode("/",$validatedArr['start_date']);
            if(sizeof($startDateArray) == 3) {
                $validatedArr['start_time_formatted'] = $startDateArray[2]."-".$startDateArray[1]."-".$startDateArray[0]."T".
                                             $validatedArr['start_time'].":00Z";
            }
            $zoomResponse = $this->createZoomMeeting($validatedArr);
            if(isset($zoomResponse['success']) && $zoomResponse['success'] == 201) {
                $course =  $this->courseRepository->find($courseId);
                
                $final =array_merge_recursive($validatedArr, $zoomResponse['data']);
                $this->repository->saveRecord($final);
                $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
                $id = $this->repository->getKeyId();          
                // if(count($course->learners)) {
                //     $session = LiveSession::findOrFail($id);
                //     $findVal = $session->lecture_id === null ? 'session_'.$id : 'lsession_'.$id;
                //     $this->clRepo->addSessionToCourseLearnerCompleted($session->lecture_id,$findVal,$courseId);
                // }
                
                if ($request->input('btnSaveNew')) {
                    return redirect()->route('member.course.live-session.create', $courseId)
                    ->with(
                        'success',
                        __('Live session has been successfully saved')
                    );
                } elseif ($request->input('btnSave')) {
                        return redirect()->route('member.course.live-session.edit', $id)
                        ->with(
                            'success',
                            __('Live session has been successfully created.')
                        );
                } else {
                        return redirect(route('member.course.show', $courseId).'#nav-zoom')
                        ->with(
                            'success',
                            __('Live session has been successfully saved.')
                        );
                }
            } else {
                return redirect()->route('member.course.live-session.create', $courseId)
                    ->with(
                        'error',
                        __('An error occured while creating a meeting in Zoom.')
                    );
            }
        }     
    }

    public function createZoomMeeting(Array $validatedArray)
    {
        $path = 'users/me/meetings';
        $response = $this->zoomPost($path, [
            'topic' => $validatedArray['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE, //should be default for now
            'start_time' => $this->toZoomTimeFormat($validatedArray['start_time_formatted']),
            'duration' => $validatedArray['duration'], //in minutes
            'agenda' => $validatedArray['agenda'],
            "password"=> $validatedArray['passcode'],
            'timezone' => 'Asia/Rangoon',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'waiting_room' => false
            ]
        ]);

        if($response->status() === 201) {
            return [
                'success' => $response->status(),
                'data' => json_decode($response->body(), true),
            ];
        } else {
           
            return [
                'error' => $response->status(),
                'data' => json_decode($response->body(), true),
            ];
        }
    }

    public function getZoomMeetingById(string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        // if ($response->ok()) {
        //     $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        // }

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function update(RequestLiveSession $request, $sessionId)
    {   
        $validatedArr = $request->validated();
        if($validatedArr) {           
            $startDateArray = explode("/",$validatedArr['start_date']);
            if(sizeof($startDateArray) == 3) {
                $validatedArr['start_time_formatted'] = $startDateArray[2]."-".$startDateArray[1]."-".$startDateArray[0]."T".
                                             $validatedArr['start_time'].":00Z";
            }
            $zoomResponse = $this->updateZoomMeeting($validatedArr, $validatedArr['meeting_id']);
            if(isset($zoomResponse['success']) && 
                ($zoomResponse['success'] == 200 || $zoomResponse['success'] == 201 || $zoomResponse['success'] == 204) ) {
                $session = $this->repository->find($sessionId);
                $savedZoomData = $this->getZoomMeetingById($validatedArr['meeting_id']);        
                $final =array_merge_recursive($validatedArr, $savedZoomData['data']);
                $this->repository->saveRecord($final, $sessionId); 
                $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);          
                if ($request->input('btnSave')) {
                        return redirect()->route('member.course.live-session.edit', $sessionId)
                        ->with(
                            'success',
                            __('Live session has been successfully updated.')
                        );
                } else {
                        return redirect(route('member.course.show', $session->course_id).'#nav-zoom')
                        ->with(
                            'success',
                            __('Live session has been successfully saved.')
                        );
                }
            } else {
                return redirect()->route('member.course.live-session.edit', $sessionId)
                    ->with(
                        'error',
                        __('An error occured while updating a meeting in Zoom. ')
                    );
            }
        }     
    }

    public function updateZoomMeeting(Array $validatedArray, string $id)
    {       
        $path = 'meetings/' . $id;
        $response = $this->zoomPatch($path, [
            'topic' => $validatedArray['topic'],
            'start_time' => $this->toZoomTimeFormat($validatedArray['start_time_formatted']),
            'duration' => $validatedArray['duration'], //in minutes
            'agenda' => $validatedArray['agenda'],
            "password"=> $validatedArray['passcode']
        ]);
// dd($response);exit;
        if($response->status() === 200 || $response->status() === 201 || $response->status() === 204) {
            return [
                'success' => $response->status(),
                'data' => null,
            ];
        } else { //dd($response->status());exit;
            return [
                'error' => $response->status(),
                'data' => null,
            ];
        }
    }

    public function destroyZoomMeeting(\Illuminate\Http\Request $request, string $id)
    { 
        $path = 'meetings/' . $id;
        $response = $this->zoomDelete($path);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-zoom')
          ->with('success', 'Successfully deleted');
    }

}
