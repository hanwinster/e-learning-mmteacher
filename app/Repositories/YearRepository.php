<?php

namespace App\Repositories;

use App\Models\Year;
use Illuminate\Http\Request;

class YearRepository
{
    protected $model;

    public function __construct(Year $model)
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
        return $this->model->orderBy('title', 'asc')->get()->pluck('title', 'id');
    }

    public static function getItemList($isDropdown = false)
    {
        $repository = new self(new Year());
        $posts = $repository->getItems();

        if ($isDropdown) {
            $posts->prepend('- Select Year -', '');
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
