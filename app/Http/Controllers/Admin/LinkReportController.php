<?php

namespace App\Http\Controllers\Admin;

use App\Models\LinkReport;
use App\Http\Requests\RequestLinkReport as Request;
use App\Http\Controllers\Controller;
use App\Repositories\LinkReportRepository;

class LinkReportController extends Controller
{
    protected $repository;

    public function __construct(LinkReportRepository $repository)
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

        return view('backend.link-report.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('admin.link-report.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestArticleCategory $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('admin.link-report.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.link-report.edit', $id)
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.link-report.index')
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

        return view('backend.link-report.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestArticleCategory $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->route('admin.link-report.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.link-report.edit', $id)
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.link-report.index')
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

        return redirect()->route('admin.link-report.index')
          ->with('success', 'Successfully deleted');
    }
}
