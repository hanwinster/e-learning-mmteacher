<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestLiveSession extends FormRequest
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
            'topic' => 'required|string',
            'start_date' => 'required|string|date_format:d/m/Y',
            'start_time' => 'required|string|date_format:H:i',
            'agenda' => 'nullable|string',
            'course_id' => 'required|integer',
            'lecture_id' => 'nullable|integer',
            'passcode'=> 'required|string',
            'duration' => 'required'
        ];
        if (Request::isMethod('put')) {
            $rules += ['meeting_id' => 'required'];
        }
        return $rules;
    }

}
