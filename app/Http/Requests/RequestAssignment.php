<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestAssignment extends FormRequest
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

        $rules = [
            // 'title' => 'required|string',
            // 'course_id' => 'required|integer|min:1',
            'question_id' => 'nullable|integer|min:1',
        ];
        
        if (Request::isMethod('post')) {
            $rules += ['attached_file' => 
                                [
                                    'required',
                                    'assignment_extension',
                                    // function($attribute, $value, $fail) {
                                    //     if (in_array($value->getClientOriginalExtension(), ['pdf', 'ppt', 'mp3', 'mp4'])) {
                                    //         \Log::info('true');
                                    //         return true;
                                    //     }
                                    //     // \Log::info('false');
                                    //     // return false;
                                    // },
                                ]
                            ];
        }else{
            $rules += ['attached_file' => 'assignment_extension'];

        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attached_file.required' => 'Attachement is required.',
            'attached_file.lecture_extension'  => 'Attachement file type is invalid.',
        ];
    }
}
