<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;

class RequestQuestion extends FormRequest
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
            'title' => 'required|string',
            'quiz_id' => 'required|integer|min:1',
            'user_id' => 'nullable|integer|min:1',
            'quiz_type' => 'required|string|max:50',
        ];

         if (Request::input('quiz_type') == Quiz::TRUE_FALSE) {
            $rules += ['true_or_false' => 'required|string|max:10'];
        } else if (Request::input('quiz_type') == Quiz::SHORT_QUESTION) { 
            $rules += ['short_answer' => 'required'];
        } else if (Request::input('quiz_type') == Quiz::LONG_QUESTION) { 
            $rules += ['long_answer' => 'required'];
            $rules += ['passing_option' => 'required']; 
        } else if (Request::input('quiz_type') == Quiz::BLANK){
            $rules += ['blank_answer' => 'required'];  
            $rules += ['sentence' => 'required'];     
            $rules += ['blank' => 'required'];    
        } else if (Request::input('quiz_type') == Quiz::MULTIPLE_CHOICE){
            $rules += ['answer_A' => 'required'];
            $rules += ['answer_B' => 'required'];
            //$rules += ['answer_C' => 'required'];
            $rules += ['right_answer' => 'required|array|min:1'];
        } else if (Request::input('quiz_type') == Quiz::REARRANGE) {
            $rules += ['answer_one' => 'required'];
            $rules += ['answer_two' => 'required'];
           // $rules += ['answer_three' => 'required'];
        } else if (Request::input('quiz_type') == Quiz::MATCHING) {
            $rules += ['matching_A' => 'required'];
            $rules += ['matching_B' => 'required'];
            //$rules += ['matching_C' => 'required'];

            $rules += ['matching_One' => 'required'];
            $rules += ['matching_Two' => 'required'];
            //$rules += ['matching_Three' => 'required'];

        } else { //assignment
            if (Request::isMethod('post')) {
                $rules += ['attached_file' => 
                                    [
                                        'required',
                                        'assignment_extension'
                                    ]
                        ];
            } else {
                $rules += ['attached_file' => 'assignment_extension'];
        
            }
        }
       
        return $rules;
    }
}
