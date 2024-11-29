<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Certificate;
use App\User;
use Carbon\Carbon;
use DB;


class CertificateRepository
{
    protected $model;

    public function __construct(Certificate $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } 

        $this->model->title = $request->title;
        $this->model->course_id = $request->course_id;
        $this->model->description = $request->description;
        $this->model->certify_text = $request->certify_text;
        $this->model->completion_text = $request->completion_text;
        $this->model->certificate_date = $request->certificate_date;
        $this->model->save();  
        // if ($request->file('signature_1')) {
        //     $this->model->addMediaFromRequest('signature_1')->toMediaCollection('certificate_signature_1');
        // }
        // if ($request->file('signature_2')) {
        //     $this->model->addMediaFromRequest('signature_2')->toMediaCollection('certificate_signature_2');
        // }
        // if ($request->file('background_image')) {
        //     $this->model->addMediaFromRequest('background_image')->toMediaCollection('certificate_background_image');
        // }
        // if ($request->file('logo_image')) {
        //     $this->model->addMediaFromRequest('signature_1')->toMediaCollection('certificate_logo_image');
        // }

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
        $posts = $this->model->where('course_id', $course_id)
                                //->orderBy('lecture_id')
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
    public static function canEdit($certificate)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($certificate->course_id);
        if (count($certificate->assignment_user) == 0){
            return true;
        }

        return false;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canReview($certificate)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($certificate->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }

}
