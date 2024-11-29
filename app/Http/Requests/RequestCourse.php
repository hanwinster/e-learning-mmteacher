<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RequestCourse extends FormRequest
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
        //dd(Request::input('course_categories'));exit;
        $rules = [
            /*'title' => 'required|unique:resources,title,'. $this->route('resource') . ',id',*/
            'title' => 'required',
            // 'slug' => 'required|unique:resources,slug,'. $this->route('resource') . ',id',
            'description' => 'required',
            'objective' => 'required',
            'url_link' => 'nullable|url|max:255',
            'course_categories' => 'required|array|min:1',
            'course_level_id' => 'required|integer|min:1',
            'downloadable_option' => 'required|integer|min:1',
            'video_link' => 'nullable|url|max:255',
            'grace_period_to_notify' => 'required',
            'estimated_duration' => 'required|integer|min:1',
            'estimated_duration_unit' => 'required',
            'collaborators' => 'nullable|array',
            'related_resources' => 'nullable|string'
        ];
        
        if (Request::isMethod('post')) {
            
            if(!Request::input('is_display_video')) {
                $rules += ['cover_image' => 'required|image|mimes:jpeg,jpg,png,bmp,gif,svg|max:5120'];
            } else {
                $rules += ['cover_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:5120'];
            }
            $rules += ['resource_file' => 'file|mimes:zip,rar,docx,pdf|max:5242880'];
        }else{
            $rules += ['cover_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:5120'];
            $rules += ['resource_file' => 'file|mimes:zip,rar,docx,pdf|max:5242880'];
        }
        //dd($rules);exit;
        return $rules;
    }
     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    // public function messages()
    // {   
    //     return [
    //         'cover_image.required' => 'Cover Image is required.'
    //     ];
    // }
}
