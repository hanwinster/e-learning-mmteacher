<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Http\Requests\RequestKeyword as Request;
use App\Repositories\KeywordRepository;

class KeywordController extends Controller
{
	protected $repository;
	
	public function __construct(KeywordRepository $repository)
    {
        $this->repository = $repository;

        $this->middleware('permission:view_keyword');
        $this->middleware('permission:add_keyword', ['only' => ['create','store']]);
        $this->middleware('permission:edit_keyword', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_keyword', ['only' => ['destroy']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        return view('backend.keyword.index', compact('posts'));
    }
	
	public function getKeywords()
	{
		$q = Input::get('term');

        // jQuery UI, autocomplete format, id, label, value
        $query = DB::table('keywords')->select("id", "tag AS label", "tag AS value");

        if ($q) {
            $query->where('keywords', 'LIKE', "%{$q}%");
        }

        $results = $query->get();
        $tags = array();

        if (count($results)) {
            foreach ($results as $tag) {
                $tags[] = $tag;
            }
        }

        return json_encode($tags);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.keyword.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('admin.keyword.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.keyword.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.keyword.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $lang = 'en')
    {
        $post = $this->repository->find($id);

        return view('backend.keyword.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->route('admin.keyword.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.keyword.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.keyword.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);

        $post->delete();

        return redirect()->route('admin.keyword.index')
          ->with('success', 'Successfully deleted');
    }
}
