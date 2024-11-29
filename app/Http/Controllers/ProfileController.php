<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        if (!$user = User::where('blocked', 0)
                        ->where('verified', 1)
                        ->where('approved', 1)
                        ->where('username', $username)
                        ->first()) {
            abort(404, 'User Not Found');
        };

        //$resources = $user->resources()->where('published', 1)->latest()->paginate();
        $courses = $user->courses()->latest()->paginate();
        return view('frontend.profile.show', compact('user', 'courses'));
    }
}
