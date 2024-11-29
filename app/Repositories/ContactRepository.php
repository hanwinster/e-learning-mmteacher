<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactRepository
{
    protected $model;

    public function __construct(Contact $model)
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

        if ($request->input('status') !== null) {
            $this->model->status = $request->input('status', false);
        }

        return $this->model->save();
    }
}
