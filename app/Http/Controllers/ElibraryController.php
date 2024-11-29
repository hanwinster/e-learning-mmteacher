<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\User;

class ElibraryController extends Controller
{
    protected $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user_type = currentUserType();

        $posts = $this->repository->indexForPublic(request(), $user_type);
        $subjects = SubjectRepository::getAllPublished();

        return view('frontend.elibrary.index', compact('posts', 'subjects'));
    }

    public function browse()
    {
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $how_to_slug = 'how-to-use-elibrary-for-administrators';
        } elseif ($user_type == User::TYPE_MANAGER) {
            $how_to_slug = 'how-to-use-elibrary-for-managers';
        } elseif ($user_type == User::TYPE_TEACHER_EDUCATOR) {
            $how_to_slug = 'how-to-use-elibrary-for-teacher-educators';
        } elseif ($user_type == User::TYPE_STUDENT_TEACHER) {
            $how_to_slug = 'how-to-use-elibrary-for-student-teachers';
        } else {
            $how_to_slug = 'how-to-use-elibrary-for-guests';
        }

        return view('frontend.elibrary.browse', compact('how_to_slug'));
    }
}
