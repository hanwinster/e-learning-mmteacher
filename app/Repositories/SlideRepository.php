<?php

namespace App\Repositories;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideRepository
{
    protected $model;

    public function __construct(Slide $model)
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

    public static function getPublishedSlides($limit = 5)
    {
        $repository = new self(new Slide());

        $posts = $repository->model->with(['media'])
                                    ->isPublished()
                                    ->orderBy('weight', 'desc')
                                    ->latest()
                                    ->limit($limit)
                                    ->get();

        return $posts;
    }

    /* KHK Start */
    public function apiPublishedOnly(Request $request)
    {
        $posts = $this->model
                        //->withSearch($request->input('search'))
                        //->sortable(['updated_at' => 'desc'])
                        ->isPublished()
                        ->orderBy('weight', 'desc')
                        ->latest()
                        ->paginate(5);

        return $posts;
    }
    /* KHK End */

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
        return $this->model->get()->pluck('title', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Slide());
        $posts = $repository->getItems();
        $posts->prepend('-Select Slide-', '');

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

        if ($request->file('uploaded_file')) {
            $this->model->addMediaFromRequest('uploaded_file')->toMediaCollection('slides');
        }
    }
}
