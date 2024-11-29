<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPage extends FormRequest
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
            'title' => 'required|unique:pages,title,' . $this->route('page') . ',id',
            'slug' => 'max:255|unique:pages,slug,' . $this->route('page') . ',id',
            'body' => 'required',
        ];
    }
}
