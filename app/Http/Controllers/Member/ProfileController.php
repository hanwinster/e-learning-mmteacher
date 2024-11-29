<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Repositories\UserRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\CollegeRepository;
use App\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('frontend.member.profile.dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $post = auth()->user();

        $user_types = UserRepository::getUserTypes();

        $privilege_types = UserRepository::getTypes(false);

        $subjects = SubjectRepository::getAllPublished();

        $ec_colleges = CollegeRepository::getItemList(true);

        $type = auth()->user()->type;

        $hideUserTypeAndEducationCollege = true;

        // if ($type == User::TYPE_STUDENT_TEACHER
        // || $type == User::TYPE_MANAGER
        // || $type == User::TYPE_TEACHER_EDUCATOR) {
        //     $hideUserTypeAndEducationCollege = true;
        // }
        if ($type == User::TYPE_ADMIN) {
            $hideUserTypeAndEducationCollege = false;
        }
        $hideEc = false; //echo $type; exit;
        $userType = auth()->user()->user_type;
        if ($type == User::TYPE_JOURNALIST
            || $type == User::TYPE_LEARNER 
            || $type == User::TYPE_INDEPENDENT_TEACHER
            || $userType == User::TYPE_UNESCO_STAFF) {
            $hideEc = true;
        }
        //dd($post);exit;
        return view(
            'frontend.member.profile.profile',
            compact('post', 'user_types', 'privilege_types', 'subjects', 'ec_colleges', 'hideUserTypeAndEducationCollege', 'hideEc')
        );
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'bail|required|alpha_dash|max:255|unique:users,id,' . auth()->user()->id,
            'email' => 'bail|required|nullable|email|max:255|unique:users,id,' . auth()->user()->id,
            'mobile_no' => 'nullable|min:9',
        ]);

        //$user->name = $request->input('name');
        //$user->save();
        $this->saveRecord($request, $user);

        return redirect()->route('member.profile.edit')
          ->with('success', 'Your profile has been successfully updated.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        $user = auth()->user();

        return view('frontend.member.profile.change-password', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'bail|required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!(Hash::check($request->get('password'), auth()->user()->password))) {
            // The passwords matches
            return redirect()->back()->with('error', 'Your current password does not matches with the password you provided. Please try again.');
        }
        if (strcmp($request->get('password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with('error', 'New Password cannot be same as your current password. Please choose a different password.');
        }

        //Change Password
        $user = auth()->user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully !');
    }

    public function saveRecord($request, $row)
    {
        $row->fill([
            'name' => $request->input('name', ''),
            'username' => $request->input('username', ''),
            'email' => $request->input('email', ''),
            'mobile_no' => $request->input('mobile_no', ''),
            // 'ec_college' => $request->input('ec_college', ''),
            //'user_type' => $request->input('user_type'),
        ]);

        if ($request->input('ec_college') !== null) {
            $row->ec_college = $request->input('ec_college', '');
        }

        if ($request->input('user_type') !== null) {
            $row->user_type = $request->input('user_type', '');
        }

        if ($request->input('suitable_for_ec_year') !== null) {
            $row->suitable_for_ec_year = implode(',', $request->input('suitable_for_ec_year'));
        }

        /*
        if ($request->input('notification_channel') !== null) {
            $row->notification_channel = $request->input('notification_channel', 'sms');
        }
        */
        $row->notification_channel = 'email';
        // if (in_array('sms', $request->input('notification_channel'))) {
        //     $row->notification_channel = 'sms';
        // } else {
        //     $row->notification_channel = 'email';
        // }

        $row->subscribe_to_new_resources = $request->input('subscribe_to_new_resources', 0);

        if ($request->profile_image) {
            $row->addMediaFromRequest('profile_image')
                ->withCustomProperties(['file_extension' => $request->profile_image->extension()])
                ->toMediaCollection('profile');
        }

        $row->subjects()->detach();
        $row->subjects()->attach($request->input('subjects'));

        $row->save();
    }
}
