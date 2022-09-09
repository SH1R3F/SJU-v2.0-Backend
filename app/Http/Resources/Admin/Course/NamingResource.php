<?php

namespace App\Http\Resources\Admin\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class NamingResource extends JsonResource
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
          'id'             => $this->id,
          'name_ar'        => $this->name_ar,
          'name_en'        => $this->name_en,
          'description_ar' => $this->description_ar,
          'description_en' => $this->description_en,
          'status'         => $this->status,
          'image'          => $this->image ? asset("storage/courses/namings/images/{$this->image}") : null,
          'created_at'     => $this->created_at->format('d / m / Y')
        ];
    }
}
