<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestYear extends FormRequest
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
            'title' => 'required|max:50|unique:years,title,'. $this->route('year') . ',id',
            //'slug' => 'required|unique:years,slug,'. $this->route('year') . ',id',
        ];
    }
}
