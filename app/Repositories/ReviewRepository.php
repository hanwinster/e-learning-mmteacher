<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Notifications\ReviewPosted;
use Notification;

class ReviewRepository
{
    protected $model;

    public function __construct(Review $model)
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
        return $this->model->with('resources')
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
            $this->model->published = true;
        }

        $this->model->fill($request->all());

        if ($request->input('published') !== null) {
            $this->model->published = $request->input('published', false);
        }

        $this->model->save();

        $resource = Resource::findOrFail($this->model->resource_id);

        Notification::send($resource->user, new ReviewPosted($resource, $this->model));

        return $this->model;
    }
}
