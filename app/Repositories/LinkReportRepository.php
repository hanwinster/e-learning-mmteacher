<?php

namespace App\Repositories;

use App\Models\LinkReport;
use Illuminate\Http\Request;

class LinkReportRepository
{
    protected $model;

    public function __construct(LinkReport $model)
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
                        ->with(['resource', 'user'])
                        ->has('resource')
                        ->has('user')
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
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->fill($request->all());

        if ($request->input('status') !== null) {
            $this->model->status = $request->input('status', false);
        }

        $this->model->save();

        return $this->model;
    }
}
