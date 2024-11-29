<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Summary;
//use App\Jobs\UploadVideoToVimeo;
use App\User;
use Carbon\Carbon;
use DB;


class SummaryRepository
{
    protected $model;

    public function __construct(Summary $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->title = $request->title;
        $this->model->course_id = $request->course_id;
        $this->model->description = $request->description;
        $this->model->lecture_id = $request->lecture_id;
        $this->model->save();  

        // Upload to Vimeo (if the file is video)
        $allowExtensions = ['mp4', 'mpg', 'mpeg', 'wmv', 'avi', 'mov'];

        if ( $request->file('attached_file') ) {
            $extension = $request->file('attached_file')->getClientOriginalExtension(); //->getMimeType();
            $mimes_type = $request->file('attached_file')->getMimeType();
            \Log::info($mimes_type);
            // Store media file first
            $med = $this->model->addMediaFromRequest('attached_file')->toMediaCollection('summary_attached_file');


            if (in_array($extension, $allowExtensions)) {              
               // dispatch(new UploadVideoToVimeo($this->model, 'summary_attached_file'));
            } else {
               
                $media = $this->model->getMedia('summary_attached_file');
                foreach ($media->toArray() as $val) {
                    $client = new \Google_Client();
                    $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
                    $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
                    $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                    $service = new \Google_Service_Drive($client);

                    $file = new \Google_Service_Drive_DriveFile(array(
                        'name' => $val['file_name'],
                        'parents' => array(env('GOOGLE_DRIVE_FOLDER_ID'))
                    ));
                    $result = $service->files->create($file, array(
                        'data' => file_get_contents(public_path("storage/" . $val['id'] . "/" . $val['file_name'])),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'media'
                    ));
                    $med->setCustomProperty('gdrive_link', 'https://drive.google.com/open?id=' . $result->id);
                    // get url of uploaded file
                    $url = 'https://drive.google.com/open?id=' . $result->id;
                }
                $med->save();
            }
        }
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getByCourse($request, $course_id)
    {
        $posts = $this->model->where([
                                ['course_id', $course_id],
                                ['lecture_id','!=', NULL] ])
                                ->orderBy('lecture_id')
                                ->latest()
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }

    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($summary)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        return true;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canReview($summary)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($summary->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }

    public function getForOnlyCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                            ->whereNull('lecture_id')
                            ->get();
        return $posts;
    }

    

}
