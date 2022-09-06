<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
          'id'          => $this->id,
          'fullName'    => $this->username,
          'username'    => $this->username,
          'avatar'      => 'https://pickaface.net/gallery/avatar/20130319_083314_1174_admin.png',
          'email'       => $this->email,
          'role'        => $this->roles()->first()->name,
          'permissions' => $this->permissions,
          'ability'     => [
            [
              'action'  => 'manage',
              'subject' => 'all',
            ],
          ],
          'extras'      => [
            'eCommerceCartItemsCount' => 0
          ]
        ];

        
    }
}
