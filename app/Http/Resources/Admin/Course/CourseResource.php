<?php

namespace App\Http\Resources\Admin\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
          'id' => $this->id,
          'SN' => $this->SN,
          'name_ar' => $this->name_ar,
          'name_en' => $this->name_en,
          'region' => $this->region,
          'type' => $this->type->name_ar ?? $this->type,
          'type_id' => $this->type_id,
          'category' => $this->category->name_ar,
          'category_id' => $this->category_id,
          'gender' => $this->gender->name_ar,
          'gender_id' => $this->gender_id,
          'location' => $this->location->name_ar,
          'location_id' => $this->location_id,
          'map_link' => $this->map_link,
          'map_latitude' => $this->map_latitude,
          'map_longitude' => $this->map_longitude,
          'seats' => $this->seats,
          'date_from' => $this->date_from->format('Y/m/d'),
          'date_to' => $this->date_to->format('Y/m/d'),
          'time_from' => $this->time_from,
          'time_to' => $this->time_to,
          'date' => $this->date_from->format('Y/m/d'), // . '-' . $this->date_to->format('Y/m/d'),
          'days' => $this->days,
          'minutes' => $this->minutes,
          'percentage' => $this->percentage,
          'price' => $this->price,
          'images' => $this->images,
          'trainer' => $this->trainer,
          'summary' => $this->summary,
          'content' => $this->content,
          'zoom' => $this->zoom,
          'youtube' => $this->youtube,
          'template' => $this->template,
          'template_id' => $this->template_id,
          'questionnaire' => $this->questionnaire,
          'questionnaire_id' => $this->questionnaire_id,
          'questionnaire' => $this->questionnaire,
          'attendance_duration' => $this->attendance_duration,
          'status' => $this->status,
          'created_at' => $this->created_at->format('Y/m/d')
        ];
    }
}
