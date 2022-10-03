<?php

namespace App\Models;

use App\Models\Role;
use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    public function admins()
    {
      return $this->belongsToMany(Admin::class);
    }
}
