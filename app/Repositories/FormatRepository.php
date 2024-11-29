<?php

namespace App\Repositories;

use App\Models\Format;
use Illuminate\Http\Request;

class FormatRepository
{
    protected $model;

    public function __construct(Format $model)
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
						// ->with(['media'])
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

    public function findBySlug($slug)
    {
        if (!$post = $this->model->where('slug', $slug)->first()) {
            abort(404);
        }

        return $post;
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getItems()
    {
        return $this->model->where('published', true)->get()->pluck('name', 'id');
    }

    public static function getAllPublished()
    {
        $repository = new FormatRepository(new Format());

        return $repository->model->isPublished()->get();
    }

    public static function getItemList()
    {
        $repository = new self(new Format());
        $posts = $repository->getItems();
        $posts->prepend('- Select Resource Format -', '');

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
