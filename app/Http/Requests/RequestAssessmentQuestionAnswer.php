<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestAssessmentQuestionAnswer extends FormRequest
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

        if (Request::isMethod('post')) { //echo "post"; dd(Request::all());exit;
            $rules = [
                'question' => 'required|string',
               // 'answers' => 'required|array',
                'type' =>'required',
               // 'order' => 'required|integer|min:0',
                'course_id' => 'required|integer|min:1'
               // 'right_answers' => 'required|array'
            ];
        } else { // echo "put"; dd(Request::all());exit;
            $rules = [
                'question' => 'required|string',
                'answers' => 'required|array',
                'type' =>'required',
             //   'order' => 'required|integer|min:0',
                'course_id' => 'required|integer|min:1', 
                'right_answers' => 'required|array'
            ];
        }
        
        return $rules;
    }

    
}
