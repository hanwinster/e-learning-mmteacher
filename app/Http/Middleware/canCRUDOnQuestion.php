<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\CoursePermissionRepository;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;

class canCRUDOnQuestion
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
        $quiz_id = $request->route('quiz_id') !== null ? $request->route('quiz_id') :  '';
        if ($quiz_id != null) {
            $quiz = Quiz::findOrFail($quiz_id);
            $course = Course::findOrFail($quiz->course_id);
        }
        if ($request->route('question_id')) {
            $question = Question::findOrFail($request->route('question_id'));
            $course = Course::findOrFail($question->quiz->course_id);
        }
        if ($method == 'post' || $request->is('*/profile/course-question/*/create')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to create the question for this course ID#' . $course->id . '.'));
            }
        }

        if ($method == 'delete') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the question ID#' . $question->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the question ID#' . $question->id . '.'));
            }
        }

        if ($request->is('*/profile/course-quiz/*/edit')) {
            if (!CoursePermissionRepository::canEdit($course)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the question ID#'.$question->id.'.'));
            }
        }

        return $next($request);
    }
}
