<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Resource;
use App\Repositories\ApprovalRequestRepository;

class RequestSubmitResource extends FormRequest
{
    protected $repository;

    public function __construct(ApprovalRequestRepository $repository)
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
        if ($resource = Resource::findOrFail($this->route('id'))) {
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
