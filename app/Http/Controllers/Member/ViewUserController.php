<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\User;
use App\Models\College;
use App\Http\Controllers\Controller;

class ViewUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = User::where('ec_college', '=', $user->ec_college)
            ->withType(User::TYPE_STUDENT_TEACHER)
            ->where('approved', '=', User::APPROVAL_STATUS_APPROVED)
            ->where('verified', '=', 1)
            ->where('blocked', '=', 0)
            ->withSearch(request('search'))
            ->withoutMe()
            ->latest()
            // ->sortable(['created_at' => 'desc'])
            ->paginate(request('limit'));

            $posts->appends(request()->all());
        }

        return view('frontend.member.view-user.index', compact('posts', 'college'));
    }
}
