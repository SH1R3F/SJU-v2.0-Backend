<?php

namespace App\Http\Resources\Admin\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $types = [
          'App\Models\Member'     => 'member',
          'App\Models\Subscriber' => 'subscriber',
          'App\Models\Volunteer'  => 'volunteer',
        ];

        // Avatar
        switch ($this->pivot->courseable_type) {
          case 'App\Models\Member':
            $avatar = $this->profile_image ? asset("storage/members/{$this->id}/profile_image/{$this->profile_image}") : null;
            $mobile = $this->mobile;
            break;

          case 'App\Models\Subscriber':
            $avatar = $this->image ? asset("storage/subscribers/{$this->id}/images/{$this->image}") : null;
            $mobile = $this->mobile_key . $this->mobile;
            break;

          case 'App\Models\Volunteer':
            $avatar = $this->image ? asset("storage/volunteers/{$this->id}/images/{$this->image}") : null;
            $mobile = $this->mobile_key . $this->mobile;
            break;
        }

        return [
          'id'          => $this->id,
          'fullName'    => "{$this->fname_ar} {$this->sname_ar} {$this->tname_ar} {$this->lname_ar}",
          'fullName_en' => "{$this->fname_en} {$this->sname_en} {$this->tname_en} {$this->lname_en}",
          'type'        => $types[$this->pivot->courseable_type],
          'mobile'      => $mobile,
          'email'       => $this->email,
          'passed'      => $this->pivot->attendance,
          "avatar"      => $avatar
        ];
    }
}
