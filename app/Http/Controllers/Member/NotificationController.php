<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate();
        
        return view('frontend.member.notification.index', compact('notifications'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification != null) {
            $notification->markAsRead();
        }

        return redirect()->to($notification->data['click_action_link']);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('member.notification.index')->with('error', 'Invalid Notification.');
        }

        $notification->delete();

        return redirect()->route('member.notification.index')->with('success', 'Successfully deleted.');
    }

}
