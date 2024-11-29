<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'bail|required|alpha_dash|max:255|unique:users,id,' . auth()->user()->id,
            'email' => 'bail|required|nullable|email|max:255|unique:users,id,' . auth()->user()->id,
            'mobile_no' => 'required|min:9',
        ]);

        $post = $this->saveRecord($request, $user);

        $json = ['message' => 'Your profile has been successfully updated.', 'data' => new UserResource($post)];

        return response()->json($json);
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
            return response()->json(['error' => 'Your current password does not matches with the password you provided. Please try again.']);
        }
        if (strcmp($request->get('password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return response()->json(['error' => 'New Password cannot be same as your current password. Please choose a different password.']);
        }

        $user = auth()->user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        $json = ['message' => 'Password changed successfully.', 'data' => new UserResource($user)];

        return response()->json($json);
    }

    public function saveRecord($request, $row)
    {
        $row->fill([
            'name' => $request->input('name', ''),
            'username' => $request->input('username', ''),
            'email' => $request->input('email', ''),
            'mobile_no' => $request->input('mobile_no', ''),
            'ec_college' => $request->input('ec_college', ''),
            //'user_type' => $request->input('user_type'),
        ]);

        $row->user_type = $request->input('user_type', '');

        if ($request->input('suitable_for_ec_year') !== null) {
            $row->suitable_for_ec_year = implode(',', $request->input('suitable_for_ec_year'));
        }

        /*
        if ($request->input('notification_channel') !== null) {
            $row->notification_channel = $request->input('notification_channel', 'sms');
        }
        */
        if (in_array('sms', $request->input('notification_channel'))) {
            $row->notification_channel = 'sms';
        } else {
            $row->notification_channel = 'email';
        }

        $row->subscribe_to_new_resources = $request->input('subscribe_to_new_resources', 0);

        if ($request->profile_image_in_baseb4) {
            //$row->addMediaFromBase64($request->profile_image_in_baseb4)->usingFileName(str_random(12))->toMediaCollection('profile');

            $image = 'data:image/png;base64,' . $request->profile_image_in_baseb4;

            $row->addMediaFromBase64($image)->usingFileName(str_random(12) . '.png')->withCustomProperties(['file_extension' => 'png'])->toMediaCollection('profile');
        }

        /*
        if ($request->profile_image) {
            $row->addMediaFromRequest('profile_image')->withCustomProperties(['file_extension' => $request->profile_image->extension() ])->toMediaCollection('profile');
        }*/

        $row->subjects()->detach();
        $row->subjects()->attach($request->input('subjects'));

        $row->save();

        return $row;
    }
}
