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
        $permissions = $this->roles()->first() ? $this->roles()->first()->permissions()->pluck('name') : [];
        $abilities = [
          [
            'action'  => 'read',
            'subject' => 'dashboard',
          ],
          [
            'action'  => 'read',
            'subject' => 'all',
          ],
        ];
        foreach ($permissions as $permission) {
          $permission = explode('-', $permission);
          array_push($abilities, [ 'action' => $permission[0], 'subject' => $permission[1] ]);
        }

        return [
          'id'          => $this->id,
          'username'    => $this->username,
          'avatar'      => $this->avatar ? asset("storage/admins/{$this->id}/images/{$this->avatar}") : 'https://pickaface.net/gallery/avatar/20130319_083314_1174_admin.png',
          'email'       => $this->email,
          'mobile'      => $this->mobile,
          'role'        => $this->roles()->first() ? $this->roles()->first()->display_name : null,
          'role_id'     => $this->roles()->first() ? $this->roles()->first()->id : null,
          'branch_id'   => $this->branch_id,
          'permissions' => $permissions,
          'ability'     => $abilities
        ];

        
    }
}
