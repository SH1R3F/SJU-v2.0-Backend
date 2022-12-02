<?php

namespace App\Models;

use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;
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
        'avatar'
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function scopeFilter($query, $request)
    {

        // Filter by mobile
        if ($request->mobile) {
            $query->where('mobile', 'LIKE', "%{$request->mobile}%");
        }

        // Filter by email
        if ($request->email) {
            $query->where('email', 'LIKE', "%{$request->email}%");
        }

        // Filter by username
        if ($request->username) {
            $query->where('username', 'LIKE', "%{$request->username}%");
        }

        // Filter by search
        if ($request->q) {
            $query->where('username', 'LIKE', "%{$request->q}%")
                ->orWhere('email', 'LIKE', "%{$request->q}%")
                ->orWhere('mobile', 'LIKE', "%{$request->q}%");
        }

        return $query;
    }

    public function scopeSortData($query, $request)
    {
        $sortBy   = $request->sortBy;
        $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

        return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }
}
