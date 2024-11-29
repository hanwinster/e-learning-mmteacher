<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Permission;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Repositories\UserRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\CollegeRepository;

class UserController extends Controller
{
    private $resource;

    public function __construct(User $user)
    {
        $this->resource = $user;

        $this->middleware('permission:view_user',['only' => 'view']);
        $this->middleware('permission:add_user', ['only' => ['create','store']]);
        $this->middleware('permission:edit_user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_user', ['only' => ['destroy']]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $ec_colleges = CollegeRepository::getItemList(false);

        $roles = Role::all()->pluck('name', 'id');
        
        $approvalStatus = User::APPROVAL_STATUS;

        $accessible_rights = UserRepository::getTypes(false);

        $yes_no = ['1' => 'Yes', '0' => 'No'];
        $posts = $this->resource
                        // ->where('approved', 0)
                        // ->where('blocked', 0)
                        ->sortable(['updated_at' => 'desc'])
                        ->withSearch($request->input('search'))
                        ->withCollege($request->input('ec_college'))
                        ->withType($request->input('type'))
                        ->withRole($request->input('role_name'))
                        ->withApproved($request->input('approved'))
                        ->withBlocked($request->input('blocked'))
                        ->withVerified($request->input('verified'))
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());
        if(auth()->user()->id = 14995 || auth()->user()->id = 1) {

            //dd($posts);exit;
         }
        return view(
            'backend.user.index',
            compact('posts', 'ec_colleges', 'roles', 'accessible_rights', 'approvalStatus', 'yes_no')
        );
    }

    /**
     * Display a listing of the resource.
     * Approved user list
     * @return \Illuminate\Http\Response
     */
    public function indexofApproved(Request $request)
    {
        $ec_colleges = CollegeRepository::getItemList(false);

        $posts = $this->resource->withApproved(1)
                        ->sortable(['updated_at' => 'desc'])
                        ->withSearch($request->input('search'))
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return view('backend.user.index_approved', compact('posts', 'ec_colleges'));
    }

    /**
     * Display a listing of the resource.
     * Blocked user list
     * @return \Illuminate\Http\Response
     */
    public function indexofBlocked(Request $request)
    {
        $ec_colleges = CollegeRepository::getItemList(false);

        $posts = $this->resource->withBlocked(1)
                        ->sortable(['updated_at' => 'desc'])
                        ->withSearch($request->input('search'))
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return view('backend.user.index_blocked', compact('posts', 'ec_colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        $user_types = UserRepository::getUserTypes();

        $privilege_types = UserRepository::getTypes(false);

        $subjects = SubjectRepository::getAllPublished();

        $ec_colleges = CollegeRepository::getItemList(true);
        $approvalStatus = User::APPROVAL_STATUS;

        return view(
            'backend.user.form',
            compact('roles', 'user_types', 'approvalStatus', 'subjects', 'privilege_types', 'ec_colleges')
        );
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
            'name' => 'required|string|max:255',
            'username' => 'bail|required|alpha_dash|max:255|unique:users',
            'email' => 'bail|required|nullable|email|max:255|unique:users',
           // 'mobile_no' => 'required|min:9',
            'password' => 'bail|required|confirmed|min:6',
            'type' => 'required',
            'gender' => 'required',
            'account_type' => 'required',
            'user_type' => 'required'
        ];

        $request->validate($rules);

        $row = $this->resource; // dd($row);exit;
        $this->saveRecord($request, $row);

       // $roles = $request['roles']; //commented out coz same as access rights
        $roles = Role::all()->pluck('id', 'code');
        $roleId = $row->type ? $roles[$row->type] : 0;

        if ($roleId !== 0) {
            //foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $roleId)->firstOrFail();
            $row->assignRole($role_r);
            //}
        }


        if ($request->input('btnSave')) {
            return Redirect::route('admin.user.index')
                ->with(
                    'success',
                    ' #' . $row->id . ' has been successfully saved.'
                );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.user.edit', $row->id)
                ->with(
                    'success',
                    ' #' . $row->id . ' has been successfully saved.'
                );
        } else {
            return Redirect::route('admin.user.index')
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
    public function edit($id, $lang = 'en')
    {
        if ($id == 1 && auth()->user()->id != $id) {
            return Redirect::route('admin.user.index')->with(
                'warning',
                'You are not allowed to edit this user account.'
            );
        }

        $post = $this->resource->findOrFail($id);

        $roles = Role::all();

        $user_types = UserRepository::getUserTypes();

        $privilege_types = UserRepository::getTypes(false);

        $subjects = SubjectRepository::getAllPublished();

        $ec_colleges = CollegeRepository::getItemList(true);
        $approvalStatus = User::APPROVAL_STATUS;

        return view(
            'backend.user.form',
            compact('post', 'roles', 'approvalStatus', 'user_types', 'subjects', 'privilege_types', 'ec_colleges')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   //dd($request->all());exit; 
        // #Credits: https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'bail|required|alpha_dash|max:255|unique:users,id,' . $id,
            'email' => 'bail|nullable|email|max:255|unique:users,id,' . $id,
           // 'mobile_no' => 'required|min:9',
            'password' => 'confirmed',
            'type' => 'required',
            'gender' => 'required',
            'account_type' => 'required',
            'user_type' => 'required'
        ];

        $request->validate($rules);

        $row = $this->resource->find($id);

        $this->saveRecord($request, $row);

        $roles = $request['roles'];

        if (isset($roles)) {
            $row->roles()->sync($roles);
        } else {
            $role_r = Role::where('id', '=', $row->role_id)->firstOrFail();
            $row->assignRole($role_r);
            //$row->roles()->detach();
        }

        if ($request->input('btnSave')) {
            return Redirect::route('admin.user.index')
                ->with(
                    'success',
                    ' #' . $row->id . ' has been successfully saved.'
                );
        } elseif ($request->input('btnApply')) {
            return Redirect::route('admin.user.edit', $row->id)
                ->with(
                    'success',
                    ' #' . $row->id . ' has been successfully saved.'
                );
        } else {
            return Redirect::route('admin.user.index')
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
        if(Userrepository::getUserCreatedCourses($id)) {
            return Redirect::back()
            ->with('error', 'Cannot delete the user as he/she has created a course/courses! Please delete them first');
        }
        if(Userrepository::getUserTakenCourses($id)) {
            return Redirect::back()
            ->with('error', 'Cannot delete the user as he/she has taken a course/courses! Please delete them first');
        }
        //delete if there's a dicussion message
        
        $post->delete();

        return Redirect::back()
            ->with('success', 'Successfully deleted');
    }

    public function saveRecord($request, $row)
    {
        $row->fill([
                'name' => $request->input('name', ''),
                'username' => $request->input('username', ''),
                'email' => $request->input('email', ''),
                'mobile_no' => $request->input('mobile_no', ''),
                'ec_college' => $request->input('ec_college', ''),
                'gender' => $request->input('gender'),
            ]);

        if ($request->input('password')) {
            $row->password = bcrypt($request->input('password'));
        }

        if ($request->input('suitable_for_ec_year') !== null) {
            $row->suitable_for_ec_year = implode(",", $request->input('suitable_for_ec_year'));
        }

        $row->type = $request->input('type', ''); // ???
        $roles = Role::all()->pluck('id', 'code');
        $row->role_id = $row->type ? $roles[$row->type] : 0;
        $row->user_type = $request->input('user_type', '');
        $row->account_type = $request->input('account_type', 0);
        $row->blocked = $request->input('blocked', 0);
        $row->verified = $request->input('verified', 0);
        $row->is_unesco_mgr = $request->input('is_unesco_mgr', 0);

        if ($request->input('verified') == 0) {
            $row->verification_code = rand(100000, 1000000);//str_random(6);
            // Send SMS or email
        }

        if ($request->input('approved') !== null) {
            $row->approved = $request->input('approved', false);
        }

        /*
        if ($request->input('notification_channel') !== null) {
            $row->notification_channel = $request->input('notification_channel', 'sms');
        }
        */

        // if (in_array('sms', $request->input('notification_channel'))) {
        //     $row->notification_channel = 'sms';
        // } else {
            $row->notification_channel = 'email'; 
        //}
 
        if ($request->profile_image) {
            $row->addMediaFromRequest('profile_image')->withCustomProperties(['file_extension' => $request->profile_image->extension() ])->toMediaCollection('profile');
        }

        $row->subjects()->detach();
        $row->subjects()->attach($request->input('subjects'));

        $row->save();
    }

    public function importForm()
    {
        return view('backend.user.import_form');
    }
}
