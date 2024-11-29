<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CoursePermissionRepository;
use App\Models\Course;

class CanCRUDOnCourse
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
        $course_id = '';
        if ($request->route('course')) { 
            $course_id = $request->route('course') ?? '';
        } else if($request->route('course_id')) {
            $course_id = $request->route('course_id') ?? '';
        }
        
        if ($course_id != null) {
            $course = Course::findOrFail($course_id);
        } 

        if ($method == 'delete') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the course ID#' . $course->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the course ID#' . $course->id . '.'));
            }
        }

        if ($request->is('*/profile/course/*/edit')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                // abort('401');
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the course ID#'.$course->id.'.'));
            }
        }

        if ($request->is('*/profile/course/*/submit')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                // abort('401');
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to submit a approval request for this course ID#'.$course->id.'.'));
            }
        } 
        return $next($request);
    }
}
