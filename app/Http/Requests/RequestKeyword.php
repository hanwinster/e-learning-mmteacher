<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestKeyword extends FormRequest
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
            // 'keyword' => 'required|max:255',
            'keyword' => 'required|max:255|unique:keywords,keyword,'. $this->route('keyword') . ',id',
            //'slug' => 'required|max:255|unique:keywords,slug,'. $this->route('keyword') . ',id',
        ];
    }
}
