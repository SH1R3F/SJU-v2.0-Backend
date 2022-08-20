<?php

namespace App\Models;

use App\Models\Admin;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    public function admins()
    {
      return $this->belongsToMany(Admin::class);
    }

    public function permissions()
    {
      return $this->belongsToMany(Permission::class);
    }
}
