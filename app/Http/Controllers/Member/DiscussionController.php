<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestDiscussion as RequestDiscussion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionRepository;
use Carbon\Carbon;


class DiscussionController extends Controller
{
    public function __construct(DiscussionRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
        $course = $this->courseRepository->find($course_id);
       
        return view('frontend.member.discussion.form', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestDiscussion  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestDiscussion $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.course.discussion.create', $id)
              ->with(
                  'success',
                  __('Discussion has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-discussion')
              ->with(
                  'success',
                  __('Discussion has been successfully saved.')
              );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discussion = $this->repository->find($id);
        return view('frontend.member.course.discussion.show', compact('discussion'));
    }

    /**
     * Display the user's assignment.
     *
     * @param  int  $discusstionId
     * @return \Illuminate\Http\Response
     */
    public function discussionParticipant($discusstionId)
    {
        // $certificate = $this->repository->find($id);
        // $user_certificates = CertificateUser::where('assignment_id', $id)->paginate();
        // return view('frontend.member.course.certificate.user_certificate', compact('certificate', 'user_certificates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $discusstionId
     * @return \Illuminate\Http\Response
     */
    public function edit($discusstionId)
    {
        $post = $this->repository->find($discusstionId);
        $course = $this->courseRepository->find($post->course_id);
        return view('frontend.member.discussion.form', compact('course', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestDiscussion  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestDiscussion $request, $id)
    {
        $validated = $request->validated();
        //dd($request->all());exit; 
        $discussion = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.course.discussion.edit', [$id])
              ->with(
                  'success',
                  __('Discussion setting has been successfully updated.')
              );
        } else {
            return redirect(route('member.course.show', $discussion->course_id).'#nav-discussion')
              ->with(
                  'success',
                  __('Discussion setting has been successfully updated.')
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
        //$post->assignment_user->each->delete();
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-discussion')
          ->with('success', 'Successfully deleted');
    }

}
