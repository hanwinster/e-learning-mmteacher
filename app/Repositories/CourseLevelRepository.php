<?php

namespace App\Repositories;

use App\Models\CourseLevel;
use Illuminate\Http\Request;

class CourseLevelRepository
{
    protected $model;

    public function __construct(CourseLevel $model)
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
        }

        $this->model->fill($request->all());
        
        $this->model->save();
    }

    public function getCourseLevels($lang)
    { 
        $repository = new self(new CourseLevel());
        $posts = $repository->getItems();
        if($lang == 'my-MM') {
            $posts = $posts->map(function($post) {         
                return trans($post);
            });
        } else {
            $posts = $posts->map(function($post) {         
                return $post;
            });
        }
        return $posts;
    }

    public function getItems()
    {
        return $this->model->orderBy('id', 'asc')->get()->pluck('value', 'id');
    }
}
