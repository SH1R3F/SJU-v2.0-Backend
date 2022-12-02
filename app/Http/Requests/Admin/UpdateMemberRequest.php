<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMemberRequest extends FormRequest
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
            // Account information
            'memberEmail' => [
                'nullable',
                'email',
                Rule::unique('members')->ignore($this->member)
            ],
            'password'    => 'nullable|min:6|confirmed',

            // Personal information
            'national_id'          => 'nullable|min:3',
            'source'               => 'nullable|min:3',
            'date'                 => 'nullable|date|min:3',
            'membership_number'    => 'nullable|min:3',
            'fname_ar'             => 'nullable|min:3',
            'sname_ar'             => 'nullable|min:3',
            'tname_ar'             => 'nullable|min:3',
            'lname_ar'             => 'nullable|min:3',
            'fname_en'             => 'nullable|min:3',
            'sname_en'             => 'nullable|min:3',
            'tname_en'             => 'nullable|min:3',
            'lname_en'             => 'nullable|min:3',
            'gender'               => 'nullable|in:0,1',
            'birthday_meladi'      => 'nullable|date',
            'birthday_hijri'       => 'nullable|date',
            'nationality'          => 'nullable',
            'qualification'        => 'nullable|min:3',
            'major'                => 'nullable|min:3',
            'journalist_job_title' => 'nullable|min:3',
            'journalist_employer'  => 'nullable|min:3',
            'newspaper_type'       => 'nullable',
            'job_title'            => 'nullable|min:3',
            'employer'             => 'nullable|min:3',

            // Contact information
            'worktel'         => 'nullable',
            'worktel_ext'     => 'nullable',
            'fax'             => 'nullable',
            'fax_ext'         => 'nullable',
            'mobile'          => 'nullable',
            'post_box'        => 'nullable|min:3',
            'post_code'       => 'nullable|min:3',
            'city'            => 'nullable',

            // Experiences and fields


            // Files and update requests
            // 'national_image'                 => 'nullable',
            // 'employer_letter'                => 'nullable',
            // 'newspaper_license'              => 'nullable',
            // 'job_contract'                   => 'nullable',
            // 'updated_personal_information'   => 'nullable|boolean',
            // 'updated_profile_image'          => 'nullable|boolean',
            // 'updated_national_image'         => 'nullable|boolean',
            // 'updated_employer_letter'        => 'nullable|boolean',
            // 'updated_experiences_and_fields' => 'nullable|boolean',

            // Membership options
            'membership_type'       => 'nullable|integer',
            'membership_start_date' => 'nullable|date',
            'membership_end_date'   => 'nullable|date',
            'invoice_id'            => 'nullable',
            'invoice_status'        => 'nullable|integer',
            'active'                => 'nullable|integer',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
