<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioResource extends JsonResource
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
          'file'       => $this->file ? asset("storage/studio/{$this->type}/{$this->file}") : null,
          'type'       => $this->type,
          'created_at' => $this->created_at->format('d / m / Y'),
          'updated_at' => $this->updated_at->format('d / m / Y')
        ];
    }
}
