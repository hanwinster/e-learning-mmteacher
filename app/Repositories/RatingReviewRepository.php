<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\RatingReview;
use Illuminate\Http\Request;
//use App\Notifications\ReviewPosted;
use Notification;

class RatingReviewRepository
{
    protected $model;

    public function __construct(RatingReview $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function publishedOnly(Request $request)
    {
        return $this->model->with('courses')
                            ->isPublished()
                            ->paginate();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->fill($request->all());

        $this->model->save();

        //$course = Course::findOrFail($this->model->course_id);

        //Notification::send($resource->user, new ReviewPosted($resource, $this->model));

        return $this->model;
    }
}
