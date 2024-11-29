<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RoleController extends Controller
{
    private $resource;

    public function __construct(Role $role)
    {
        $this->resource = $role;

        $this->middleware('permission:view_role',['only' => 'view']);
        $this->middleware('permission:add_role', ['only' => ['create','store']]);
        $this->middleware('permission:edit_role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_role', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->resource
        // ->sortable(['created_at' => 'desc'])
        ->withSearch($request->input('search'))
        ->paginate($request->input('limit'));
        
        $posts->appends($request->all());
       // $this->resetPermissions('admin');
        return view('backend.role.index', compact('posts'));
    }

    public function resetPermissions($roleCode)
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
          $p = Permission::where('id', '=', $permission)->firstOrFail();
          $role = Role::where('code', '=', $roleCode)->first();
          $role->givePermissionTo($p);
        }   // TO ADD A NEWLY ADDED PERMISSION FOR SITE ADMIN, if not, permission error!!
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('backend.role.form', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
        $request,
        [
            'name'=>'required|unique:roles|max:20',
            'permissions' =>'required',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;
        $permissions = $request['permissions'];
        $role->save();

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }

        if ($request->input('btnSave')) {
            return Redirect::route('admin.role.index')
              ->with(
                  'success',
                $role->name . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.role.edit', $role->id)
              ->with(
                  'success',
                $role->name . ' has been successfully updated.'
              );
        } else {
            return Redirect::route('admin.role.index')
              ->with(
                  'success',
                $role->name . ' has been successfully saved.'
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

        $permissions = Permission::all();

        return view('backend.role.form', compact('post', 'permissions'));
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
        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:20|unique:roles,name,'.$id,
            'permissions' => 'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role->name = $request->input('name');
        $role->save();

        $p_all = Permission::all();

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p);
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form permission in db
            $role->givePermissionTo($p);
        }


        if ($request->input('btnSave')) {
            return Redirect::route('admin.role.index')
              ->with(
                  'success',
                ' #' . $role->id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.role.edit', $role->id)
              ->with(
                  'success',
                ' #' . $role->id . ' has been successfully saved.'
              );
        } else {
            return Redirect::route('admin.role.index')
              ->with(
                  'success',
                ' #' . $role->id . ' has been successfully saved.'
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

        return Redirect::route('admin.role.index')
          ->with('success', 'Successfully deleted');
    }
}
