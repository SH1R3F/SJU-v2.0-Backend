<?php

namespace App\Models;

use App\Models\Role;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, LaratrustUserTrait, HasFactory, Notifiable;

    protected $guard = 'api-admins';

    protected $fillable = [
        'username',
        'email',
        'mobile',
        'branch_id',
        'password',
    ];

    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
      return $this->belongsToMany(Permission::class);
    }
}
