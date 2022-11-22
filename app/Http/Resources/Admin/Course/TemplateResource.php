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
          'id'                 => $this->id,
          'name'               => $this->name,
          'tmp_file'           => $this->file ? asset("storage/{$this->file}") : null,
          'preview'            => $this->file_preview ? asset("storage/courses/templates/{$this->file_preview}") : null,
          'language'           => $this->language,
          'layout'             => $this->layout,
          'fields'             => $this->fields ? $this->fields : [],
          'with_title'         => $this->with_title,
          'with_title'         => $this->with_title,
          'male_title'         => $this->male_title,
          'female_title'       => $this->female_title,
          'certcode'           => $this->certcode,
          'code_margin_top'    => $this->code_margin_top,
          'code_margin_right'  => $this->code_margin_right,
          'code_margin_bottom' => $this->code_margin_bottom,
          'code_margin_left'   => $this->code_margin_left,
          'created_at'         => $this->created_at ? $this->created_at->format('d / m / Y') : $this->created_at
        ];
    }
}
