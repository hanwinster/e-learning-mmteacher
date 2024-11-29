<?php

namespace App\Http\Controllers\Admin;

use App\Models\Year;
use App\Http\Requests\RequestYear as Request;
use App\Http\Controllers\Controller;
use App\Repositories\YearRepository;
use Spatie\MediaLibrary\Models\Media;

class YearController extends Controller
{
    protected $repository;

    public function __construct(YearRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        return view('backend.year.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.year.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestYear $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('admin.year.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.year.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.year.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->repository->find($id);

        return view('backend.year.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestYear $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->route('admin.year.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.year.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.year.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);

        $post->delete();

        return redirect()->route('admin.year.index')
          ->with('success', 'Successfully deleted');
    }
}
