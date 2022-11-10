<?php

namespace App\Http\Resources\Admin\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionnaireResource extends JsonResource
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
          'id'        => $this->id,
          'name_ar'   => $this->name_ar,
          'name_en'   => $this->name_en,
          'status'    => $this->status,
          'questions' => $this->questions()->orderBy('order')->get()
        ];
    }
}
