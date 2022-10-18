<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
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
          'id'               => $this->id,
          'blog_category_id' => $this->blog_category_id,
          'category'         => $this->category,
          'title_ar'         => $this->title_ar,
          'title_en'         => $this->title_en,
          'photos'           => $this->photos ? $this->photos : [],
          'summary_ar'       => $this->summary_ar,
          'summary_en'       => $this->summary_en,
          'content_ar'       => $this->content_ar,
          'content_en'       => $this->content_en,
          'post_date'        => Carbon::parse($this->post_date)->format('d / m / Y'),
        ];
    }
}
