<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestCourseEvaluation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => 'required|max:255|unique:course_evaluations,question,' . $this->route('course_evaluation') . ',id',
            'order' => 'required|integer|min:0|unique:course_evaluations,order,' . $this->route('course_evaluation') . ',id'
        ];
    }
}
