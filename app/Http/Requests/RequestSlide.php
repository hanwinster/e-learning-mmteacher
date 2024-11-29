<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestSlide extends FormRequest
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
            'title' => 'required',
            'weight' => 'integer|min:0'			
            //'title' => 'required|unique:slides,title,'. $this->route('slide') . ',id',
        ];
        if (Request::isMethod('post')) {
            $rules += ['uploaded_file' => 'required'];
        }
        return $rules;
    }
}
