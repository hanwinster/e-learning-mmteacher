<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestFaqCategory extends FormRequest
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
            'title' => 'required|unique:faq_categories,title,' . $this->route('faq_category') . ',id',
            // 'slug' => 'required|unique:faq_categories,slug,'. $this->route('faq_category') . ',id',
        ];
    }
}
