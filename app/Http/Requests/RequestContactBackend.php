<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestContactBackend extends FormRequest
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
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'phone_no' => 'nullable',
            'organization' => 'nullable',
            'region_state' => 'nullable', 
          //  'phone_no' => 'required',
           // 'organization' => 'required',
           // 'region_state' => 'required',
        ];
    }
}
