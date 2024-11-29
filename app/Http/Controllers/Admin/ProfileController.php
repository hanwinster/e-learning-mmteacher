<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $user;
    protected $member;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getEditProfile()
    {
        $post = Auth::user();
        $member = Member::where('user_id', $post->id)->first();

        if (is_null($post)) {
            return Redirect::to('/')
              ->with('error', 'User does\'t exist.');
        }

        return view('frontend.profile.profile', compact('post', 'member'));
    }

    public function showDashboard()
    {
        $post = Auth::user();
        $member = Member::where('user_id', $post->id)->first();

        if (is_null($post)) {
            return Redirect::to('/')
              ->with('error', 'User does\'t exist.');
        }

        return view('frontend.profile.dashboard', compact('post', 'member'));
    }

    public function postEditProfile(Request $request)
    {
        $rules = array(
			'name' => 'required',
			'email' => [
				'bail',
				'required',
				'email',
				Rule::unique('users')->ignore(auth()->user()->id)
			],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = Auth::user();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return Redirect::to('/profile/edit')
          ->with('success', 'Profile has been updated.');
    }

    public function getChangePassword()
    {
        $post = Auth::user();

        if (is_null($post)) {
            return Redirect::to('/admin')
              ->with('error', 'User doesn\'t exist.');
        }

        return view(
            'backend.user.change_password',
          compact('post')
        );
    }

    public function postChangePassword(Request $request)
    {
        $rules = array(
          'current_password' => 'required',
          'password' => 'required|confirmed|min:3',
          'password_confirmation' => 'required|min:3',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = Auth::user();

        // old password matching
        if (!Hash::check(
            $request->input('current_password'),
          $user->password
        )
        ) {
            return Redirect::back()
              ->with('error', 'Current password your entered is incorrect.');
        }

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return redirect()->route('admin.profile.change-password')
          ->with('success', 'Password has been changed.');
    }
}
