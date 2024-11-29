<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->get();// ->paginate();
       // dd($notifications); exit;
        foreach($notifications as $noti) {
            $temp = $noti->data;
            unset($noti->data);
            $noti->notification_data = $temp;
        }
        return response()->json(['data' => $notifications], 200);
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

        return response()->json($notification);
    }

    public function updateStatus(Request $request,$id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if(!$notification) {
            return response()->json(['code' => 404, 'message' => 'Notification is not found'], 404);
        }
        //if ($action == 'mark-as-read') {
        $notification->markAsRead();
        //} 
        // elseif ($action == 'mark-as-unread') {
        //     $notification->read_at = null;
        //     $notification->save();
        // }

        return response()->json(['message' => 'Successfully updated!', 'data' => $notification]);
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
            return response()->json(['status' => 'error', 'message' => 'Invalid Notification.']);
        }

        $notification->delete();

        return response()->json(['status' => 'success', 'message' => 'Successfully deleted.']);
    }
}
