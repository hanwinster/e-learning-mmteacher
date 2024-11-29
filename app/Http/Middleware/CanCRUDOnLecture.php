<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CoursePermissionRepository;
use App\Models\Lecture;
use App\Models\Course;

class CanCRUDOnLecture
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
        if ($request->route('lecture_id')) {
            $lecture = Lecture::findOrFail($request->route('lecture_id'));
            $course = Course::findOrFail($lecture->course_id);
        }

        if ($method == 'post' || $request->is('*/profile/course-lecture/*/create')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to create the lecture for this course ID#' . $course->id . '.'));
            }
        }

        if ($method == 'delete') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the lecture ID#' . $lecture->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the lecture ID#' . $lecture->id . '.'));
            }
        }

        if ($request->is('*/profile/course-lecture/*/edit')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the lecture ID#'.$lecture->id.'.'));
            }
        }

        return $next($request);
    }
}
