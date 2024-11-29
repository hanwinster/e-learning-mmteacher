<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Favourite;
use App\Http\Controllers\Controller;
use App\Notifications\ResourceLiked;
use Notification;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = auth()->user()->favourites()->orderBy('created_at', 'DESC')->paginate();

        return view('frontend.member.resource.favourites', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestReview $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);

        auth()->user()->favourites()->syncWithoutDetaching([$resourceId]);

        $favourite = Favourite::where('user_id', auth()->user()->id)
            ->where('resource_id', $resourceId)
            ->first();

        // Notified to resource's owner
        Notification::send($resource->user, new ResourceLiked($resource, $favourite));

        /*
        $favourite = new Favourite();
        $favourite->user_id = auth()->user()->id;
        $favourite->resource_id = $resourceId;
        $favourite->save();
        */

        return response()->json(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($resourceId)
    {
        $resource = Resource::findOrFail($resourceId);

        auth()->user()->favourites()->detach($resourceId);

        if (request()->ajax()) {
            return response()->json(['message' => 'success']);
        }

        return redirect()->back();
    }
}
