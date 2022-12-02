<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCourseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_ar' => 'required|min:3',
            'name_en' => 'nullable|min:3',
            'region'  => 'required|min:3',
            'type_id' => 'required|integer|exists:types,id',
            'gender_id' => 'required|integer|exists:genders,id',
            'category_id' => 'required|integer|exists:categories,id',
            'location_id' => 'required|integer|exists:locations,id',
            'map_link' => 'nullable|url',
            'map_latitude' => 'nullable',
            'map_longitude' => 'nullable',
            'seats' => 'required|integer',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'days' => 'required',
            'minutes' => 'required|integer',
            'percentage' => 'required|integer',
            'price' => 'required|integer',
            'images' => 'nullable',
            'trainer' => 'required|min:3',
            'content' => 'required|min:10',
            'summary' => 'required|min:3',
            'zoom_link' => 'nullable',
            'youtube_link' => 'nullable',
            'template_id' => 'required|integer|exists:templates,id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
