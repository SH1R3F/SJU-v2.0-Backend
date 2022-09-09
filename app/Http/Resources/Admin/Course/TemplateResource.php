<?php

namespace App\Http\Resources\Admin\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
          'name'       => $this->name,
          'file'       => $this->file,
          'language'   => $this->language,
          'layout'     => $this->layout,
          'created_at' => $this->created_at->format('d / m / Y')
        ];
    }
}
