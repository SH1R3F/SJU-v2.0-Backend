<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
          'id'         => $this->id,
          'title_ar'   => $this->title_ar, 
          'title_en'   => $this->title_en, 
          'slug'       => $this->slug, 
          'content_ar' => $this->content_ar, 
          'content_en' => $this->content_en, 
          'summary_ar' => implode(' ', array_slice(explode(' ', $this->content_ar), 0, 15)) . ' ...',
          'summary_en' => implode(' ', array_slice(explode(' ', $this->content_en), 0, 15)) . ' ...'
        ];
    }
}
