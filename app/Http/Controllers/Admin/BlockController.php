<?php

namespace App\Http\Controllers\Admin;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\Admin\BlockTrait;
use App\Repositories\BlockRepository;

class BlockController extends Controller
{
    use BlockTrait;

    private $resource;

    public function __construct(Block $page)
    {
        $this->resource = $page;

        $this->middleware('permission:view_block');
        $this->middleware('permission:add_block', ['only' => ['create','store']]);
        $this->middleware('permission:edit_block', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_block', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->resource
        ->withSearch(request('search'))
        ->sortable(['created_at' => 'desc'])
        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return view('backend.block.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = BlockRepository::getRegions();

        return view('backend.block.form', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
          'title' => 'required|min:2|unique:blocks',
        ];

        $request->validate($rules);

        $row = $this->resource;
        $this->saveRecord($request, $row);

        if ($request->input('btnSave')) {
            return Redirect::route('admin.block.index')
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.block.edit', $row->id)
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
              );
        } else {
            return Redirect::route('admin.block.index')
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
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
        $post = $this->resource->findOrFail($id);

        $regions = BlockRepository::getRegions();

        return view('backend.block.form', compact('post', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // #Credits: https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
        $rules = [
          'title' => 'required|min:2',
        ];

        $request->validate($rules);

        $row = $this->resource->find($id);

        $this->saveRecord($request, $row);

        if ($request->input('btnSave')) {
            return Redirect::route('admin.block.index')
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.block.edit', $row->id)
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
              );
        } else {
            return Redirect::route('admin.block.index')
              ->with(
                  'success',
                ' #' . $row->id . ' has been successfully saved.'
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
        $post = $this->resource->findOrFail($id);

        $post->delete();

        return Redirect::route('admin.block.index')
          ->with('success', 'Successfully deleted');
    }
}
