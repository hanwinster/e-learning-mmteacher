<?php

namespace App\Repositories;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // KHK
use Illuminate\Support\Facades\Auth; // KHK

class LicenseRepository
{
    protected $model;

    public function __construct(License $model)
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
        return $this->model
                    ->isPublished()
                    ->paginate();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        if (!$post = $this->model->where('published', true)->where('slug', $slug)->first()) {
            abort(404, 'Resource Not Found');
        }

        return $post;
    }

    public function findById($id)
    {
        if (!$post = $this->model->where('published', true)->where('id', $id)->first()) {
            return null;
        }

        return $post;
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getItems()
    {
        return $this->model->where('published', true)->get()->pluck('title', 'id'); // KHK
    }

    public static function getAllPublished()
    {
        $repository = new LicenseRepository(new License());

        return $repository->model->isPublished()->get();
    }

    public static function getItemList()
    {
        $repository = new self(new License());
        $posts = $repository->getItems();
        $posts->prepend('- Select License -', '');

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

        /*
        if ($request->file('uploaded_file')) {
            $this->model->addMediaFromRequest('uploaded_file')->toMediaCollection('licenses');
        }
        */
    }
}
