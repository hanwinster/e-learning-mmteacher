<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CoursePermissionRepository;
use App\Models\Course;
use App\Models\quiz;

class canCRUDOnQuiz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = strtolower($request->method());
        $course_id = $request->route('course_id') !== null ? $request->route('course_id') :  '';
        if ($course_id != null) {
            $course = Course::findOrFail($course_id);
        }
        if ($request->route('quiz_id')) {
            $quiz = Quiz::findOrFail($request->route('quiz_id'));
            $course = Course::findOrFail($quiz->course_id);
        }
        if ($method == 'post' || $request->is('*/profile/course-quiz/*/create')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to create the quiz for this course ID#' . $course->id . '.'));
            }
        }

        if ($method == 'delete') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the quiz ID#' . $quiz->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the quiz ID#' . $quiz->id . '.'));
            }
        }

        if ($request->is('*/profile/course-quiz/*/edit')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the quiz ID#'.$quiz->id.'.'));
            }
        }

        return $next($request);
    }
}
