<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Course;
use App\Repositories\CourseApprovalRequestRepository;

class RequestSubmitCourse extends FormRequest
{
    protected $repository;

    public function __construct(CourseApprovalRequestRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($resource = Course::findOrFail($this->route('id'))) {
            if ($resource->user_id == auth()->user()->id
            || auth()->user()->type == 'admin'
            || auth()->user()->type == 'manager'
            ) {
                return true;
            }
/*             elseif ($this->repository->getResourceCount($resource->id, Resource::APPROVAL_STATUS_PENDING)) {
                return false;
            } */
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|max:255',
        ];
    }
}
