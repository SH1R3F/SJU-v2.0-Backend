<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
          'id'                             => $this->id,
          'national_id'                    => $this->national_id,
          'source'                         => $this->source,
          'date'                           => $this->date ? $this->date->format('Y/m/d') : $this->date,
          'fname_ar'                       => $this->fname_ar,
          'sname_ar'                       => $this->sname_ar,
          'tname_ar'                       => $this->tname_ar,
          'lname_ar'                       => $this->lname_ar,
          'fname_en'                       => $this->fname_en,
          'sname_en'                       => $this->sname_en,
          'tname_en'                       => $this->tname_en,
          'lname_en'                       => $this->lname_en,
          'fullName'                       => $this->fullName,
          'fullName_en'                    => $this->fullNameEn,
          'name'                           => "{$this->fname_ar} {$this->lname_ar}",
          'gender'                         => $this->gender,
          "nationality"                    => $this->nationality,
          "birthday_hijri"                 => $this->birthday_hijri ? $this->birthday_hijri->format('Y/m/d') : $this->birthday_hijri,
          "birthday_meladi"                => $this->birthday_meladi ? $this->birthday_meladi->format('Y/m/d') : $this->birthday_meladi,
          "qualification"                  => $this->qualification,
          "major"                          => $this->major,
          "journalist_job_title"           => $this->journalist_job_title,
          "journalist_employer"            => $this->journalist_employer,
          "newspaper_type"                 => $this->newspaper_type,
          "job_title"                      => $this->job_title,
          "employer"                       => $this->employer,
          "worktel"                        => $this->worktel,
          "worktel_ext"                    => $this->worktel_ext,
          "fax"                            => $this->fax,
          "fax_ext"                        => $this->fax_ext,
          "post_box"                       => $this->post_box,
          "post_code"                      => $this->post_code,
          "mobile"                         => $this->mobile,
          "fullMobile"                     => $this->mobile,
          "email"                          => $this->email,
          "branch"                         => $this->branch,
          "city"                           => $this->city,
          "experiences_and_fields"         => !is_null($this->experiences_and_fields) ? $this->experiences_and_fields : [
            'experiences' => [],
            'fields' => [],
            'languages' => [],
          ],
          "avatar"                         => $this->profile_image ? asset("storage/{$this->profile_image}") : null,
          "national_image"                 => $this->national_image ? asset("storage/{$this->national_image}") : null,
          "employer_letter"                => $this->employer_letter ? asset("storage/{$this->employer_letter}") : null,
          "newspaper_license"              => $this->newspaper_license ? asset("storage/{$this->newspaper_license}") : null,
          // "updated_personal_information"   => $this->updated_personal_information,
          // "updated_profile_image"          => $this->updated_profile_image,
          // "updated_national_image"         => $this->updated_national_image,
          // "updated_employer_letter"        => $this->updated_employer_letter,
          // "updated_experiences_and_fields" => $this->updated_experiences_and_fields,
          // "membership_type"                => $this->membership_type,
          // "membership_start_date"          => $this->membership_start_date ? $this->membership_start_date->format('Y/m/d') : $this->membership_start_date,
          // "membership_end_date"            => $this->membership_end_date ? $this->membership_end_date->format('Y/m/d') : $this->membership_end_date,
          // "invoice_id"                     => $this->invoice_id,
          // "invoice_status"                 => $this->invoice_status,

          "membership_number"              => $this->membership_number,
          'subscription'                   => $this->subscription,
          'invoice'                        => $this->invoices()->orderBy('id', 'DESC')->first(),
          'delivery_method'                => $this->delivery_method,
          'delivery_address'               => $this->delivery_address,

          'active'                         => $this->active,
          'approved'                       => $this->approved,
          'refusal_reason'                 => $this->refusal_reason,
          'courses'                        => $this->courses()->count(),
          'created_at'                     => $this->created_at->format('Y/m/d') . (App::getLocale() == 'ar' ? ' م': ''),
          'online'                         => $this->last_seen > Carbon::now()->subMinutes(5)
        ];
    }
}
