<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestArticle extends FormRequest
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
        if (Request::isMethod('post')) {
            return [
                'title' => 'required|max:255',
                'slug' => 'unique:articles,slug,'. $this->route('article') . ',id',
                'body' => 'required',
                'category_id' => 'required',
                'hyperlink' => 'required',
                'uploaded_file' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg',
            ];
        } else {
            return [
                'title' => 'required|max:255',
                'slug' => 'unique:articles,slug,'. $this->route('article') . ',id',
                'body' => 'required',
                'category_id' => 'required',
                'hyperlink' => 'required'
            ];
        }
    }
}
