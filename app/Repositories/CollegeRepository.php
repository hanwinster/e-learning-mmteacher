<?php

namespace App\Repositories;

use App\Models\College;
use Illuminate\Http\Request;

class CollegeRepository
{
    protected $model;

    public function __construct(College $model)
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

    public function getItems()
    {
        return $this->model->isPublished()->orderBy('title', 'asc')->get()->pluck('title', 'id');
    }

    public static function getItemList($isDropdown = false)
    {
        $repository = new self(new College());
        $posts = $repository->getItems();
        $posts = $posts->map(function($post) {         
            return $post;
        });
        if ($isDropdown) {
            $posts->prepend(trans('- Select Education Degree College -'), '');
        }

        return $posts;
    }

    public static function getItemListAPI($lang='en')
    {
        $repository = new self(new College());
        $posts = $repository->getItems();
        if($lang === 'en') {
            $posts = $posts->map(function($post) {         
                return $post;
            });
        } else {
            $posts = $posts->map(function($post) {         
                return trans($post);
            });
        }        
        return $posts;
    }


    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        }

        $this->model->fill($request->all());

        if ($request->input('published') !== null) {
            $this->model->published = $request->input('published', false);
        }

        $this->model->save();
    }
}
