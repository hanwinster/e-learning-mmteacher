<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Rules\CurrentMonthOnly;

class RequestResource extends FormRequest
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
        $carbon = Carbon::now();

        $rules = [
            /*'title' => 'required|unique:resources,title,'. $this->route('resource') . ',id',*/
            'title' => 'required|max:255',
            // 'slug' => 'required|unique:resources,slug,'. $this->route('resource') . ',id',
            'strand' => 'required',
            'sub_strand' => 'required',
            'author' => 'required',
            'suitable_for_ec_year' => 'required',
            'license_id' => 'required',
            'description' => 'required',
            'user_type' => 'required',
            'subjects' => 'required',
            'additional_information' => 'max:255',
            'publishing_year' => 'integer|min:1900|max:'.$carbon->format('Y'),
            'publishing_month' => ['required_with:publishing_year', new CurrentMonthOnly],
            //'cover_image' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg|max:1000',
        ];

        if (Request::isMethod('post')) {
            $rules += ['cover_image' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg|max:1000'];
        }

        return $rules;
    }
}
