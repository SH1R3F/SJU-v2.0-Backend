<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\MemberResource;
use App\Http\Resources\Admin\VolunteerResource;
use App\Http\Resources\Admin\SubscriberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalSupportResource extends JsonResource
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
          'id'           => $this->id,
          'title'        => $this->title,
          'status'       => $this->status,
          'type'         => $this->ticketable_type === 'App\Models\Volunteer' ? 'volunteer' : ( $this->ticketable_type === 'App\Models\Subscriber' ? 'subscriber' : 'member' ),
          'ticketable'   => $this->ticketable_type === 'App\Models\Volunteer' ?
                          new VolunteerResource($this->ticketable) :
                          ( $this->ticketable_type === 'App\Models\Subscriber' ? new SubscriberResource($this->ticketable) : new MemberResource($this->ticketable) ),
          'lastMessage'  => $this->chats()->orderBy('id', 'DESC')->first(),
          'firstMessage' => $this->chats()->first(),
          'chat'         => $this->chats,
          'created_at'   => $this->created_at,
          'updated_at'   => $this->updated_at,
        ];
    }
}
