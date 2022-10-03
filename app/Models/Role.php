<?php

namespace App\Models;

use App\Models\Admin;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    protected $fillable = [
      'name',
      'display_name',
      'description',
    ];

    public function admins()
    {
      return $this->belongsToMany(Admin::class);
    }

    public function permissions()
    {
      return $this->belongsToMany(Permission::class);
    }

    public function scopeFilter($query, $request)
    {
      // Filter by search
      if ($request->q) {
        $query->where('name', 'LIKE', "%{$request->q}%")
              ->orWhere('display_name', 'LIKE', "%{$request->q}%")
              ->orWhere('description', 'LIKE', "%{$request->q}%");
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
