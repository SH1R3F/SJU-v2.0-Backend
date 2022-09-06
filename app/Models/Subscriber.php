<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $guard = 'api-users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname_ar',
        'sname_ar',
        'tname_ar',
        'lname_ar',
        'fname_en',
        'sname_en',
        'tname_en',
        'lname_en',
        
        'gender',
        'country',
        'city',
        'birthday_hijri',
        'birthday_meladi',

        'qualification',
        'major',
        'job_title',
        'employer',

        'worktel',
        'worktel_ext',
        'fax',
        'fax_ext',

        'post_box',
        'post_code',

        'mobile',
        'email',
        'password',

        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter($query, $request)
    {
      if ($request->q) {
        $query->where('fname_ar', 'LIKE', "%{$request->q}%")
              ->orWhere('sname_ar', 'LIKE', "%{$request->q}%")
              ->orWhere('tname_ar', 'LIKE', "%{$request->q}%")
              ->orWhere('lname_ar', 'LIKE', "%{$request->q}%")
              ->orWhere('fname_en', 'LIKE', "%{$request->q}%")
              ->orWhere('sname_en', 'LIKE', "%{$request->q}%")
              ->orWhere('tname_en', 'LIKE', "%{$request->q}%")
              ->orWhere('lname_en', 'LIKE', "%{$request->q}%");
      }
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy == 'subscriber') {
        $query->orderByRaw("CONCAT(fname_ar, sname_ar, tname_ar, lname_ar) $sortType");
        return $query;
      }

      elseif ($sortBy == 'courses') {
        // Do some work
        return $query;
      }

      return $query->orderBy($sortBy, $sortType);
    }
}
