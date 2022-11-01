<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
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
          'id'              => $this->id,
          'national_id'     => $this->national_id,
          'fname_ar'        => $this->fname_ar,
          'sname_ar'        => $this->sname_ar,
          'tname_ar'        => $this->tname_ar,
          'lname_ar'        => $this->lname_ar,
          'fname_en'        => $this->fname_en,
          'sname_en'        => $this->sname_en,
          'tname_en'        => $this->tname_en,
          'lname_en'        => $this->lname_en,
          'fullName'        => "{$this->fname_ar} {$this->sname_ar} {$this->tname_ar} {$this->lname_ar}",
          'fullName_en'     => "{$this->fname_en} {$this->sname_en} {$this->tname_en} {$this->lname_en}",
          'name'            => "{$this->fname_ar} {$this->lname_ar}",
          'gender'          => $this->gender,
          "country"         => $this->country,
          "branch"          => $this->branch,
          "nationality"     => $this->nationality,
          "qualification"   => $this->qualification,
          "major"           => $this->major,
          "job_title"       => $this->job_title,
          "employer"        => $this->employer,
          "worktel"         => $this->worktel,
          "worktel_ext"     => $this->worktel_ext,
          "fax"             => $this->fax,
          "fax_ext"         => $this->fax_ext,
          "post_box"        => $this->post_box,
          "post_code"       => $this->post_code,
          "mobile"          => $this->mobile,
          "mobile_key"      => $this->mobile_key,
          "fullMobile"      => $this->mobile_key . $this->mobile,
          "email"           => $this->email,
          "avatar"          => $this->image ? asset("storage/volunteers/{$this->id}/images/{$this->image}") : null,
          'courses'         => 11,
          'status'          => $this->status,
          'created_at'      => $this->created_at->format('d / m / Y')
        ];
    }
}
