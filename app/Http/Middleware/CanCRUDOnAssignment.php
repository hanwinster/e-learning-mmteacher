<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CoursePermissionRepository;
use App\Repositories\AssignmentRepository;
use App\Models\Assignment;
use App\Models\Course;

class CanCRUDOnAssignment
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
        if ($request->route('assignment_id')) {
            $assignment = Assignment::findOrFail($request->route('assignment_id'));
            $course = Course::findOrFail($assignment->course_id);
        }
        if ($method == 'post' || $request->is('*/profile/course-assignment/*/create') ) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to create the assignment for this course ID#' . $course->id . '.'));
            }
        }

        if ($method == 'delete') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the assignment ID#' . $assignment->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the assignment ID#' . $assignment->id . '.'));
            }
        }

        if ($request->is('*/profile/course-assignment/*/edit') || $request->is('*/profile/assignment/*/user-assignment')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the assignment ID#'.$assignment->id.'.'));
            }
            if (!AssignmentRepository::canEdit($assignment)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You can\'t edit the assignment ID#'.$assignment->id) .' because uploaded assignment already exist for this assignment.');
            }
        }

        return $next($request);
    }
}
