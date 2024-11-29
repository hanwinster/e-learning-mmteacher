<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestCertificate extends FormRequest
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
            'title' => 'string',
            'course_id' => 'required|integer|min:1',
        ];
        
        // if (Request::isMethod('post')) {
        //     $rules += ['attached_file' => 
        //                         [
        //                             'required',
        //                             'assignment_extension',
        //                         ]
        //                     ];
        // } else {
        //     $rules += ['attached_file' => 'assignment_extension'];

        // }

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
            
        ];
    }
}
