<?php

namespace App\Models;

use App\Models\Courseable;
use App\Models\Course\Course;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Course\Certificate;
use Illuminate\Support\Facades\DB;
use App\Models\TechnicalSupportTicket;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\VerifyDifferentUsersEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Subscriber extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;


    protected $guard = 'api-subscribers';

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
        'nationality',
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
        'mobile_key',
        'email',
        'password',
        'last_seen',

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


    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyDifferentUsersEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    public function scopeFilter($query, $request)
    {

      // Filter by status
      if ($request->status) {
        $query->whereNotNull('email_verified_at');
      } else {
        $query->whereNull('email_verified_at');
      }

      // Filter by mobile
      if ($request->mobile) {
        $query->where('mobile', 'LIKE', "%{$request->mobile}%");
      }
      
      // Filter by email
      if ($request->email) {
        $query->where('email', 'LIKE', "%{$request->email}%");
      }

      // Filter by name
      if ($request->name) {
        $query->where(DB::raw("CONCAT(`fname_ar`, ' ', `sname_ar`, ' ', `tname_ar`, ' ', `lname_ar` )"), 'LIKE', "%{$request->name}%")
              ->orWhere(DB::raw("CONCAT(`fname_en`, ' ', `sname_en`, ' ', `tname_en`, ' ', `lname_en` )"), 'LIKE', "%{$request->name}%");
      }

      // Filter by search
      if ($request->q) {
        $query->where(DB::raw("CONCAT(`fname_ar`, ' ', `sname_ar`, ' ', `tname_ar`, ' ', `lname_ar` )"), 'LIKE', "%{$request->q}%")
              ->orWhere(DB::raw("CONCAT(`fname_en`, ' ', `sname_en`, ' ', `tname_en`, ' ', `lname_en` )"), 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy == 'subscriber') {
        if (app()->getLocale() == 'ar') {
          $query->orderByRaw("CONCAT(fname_ar, sname_ar, tname_ar, lname_ar) $sortType");
        } else {
          $query->orderByRaw("CONCAT(fname_en, sname_en, tname_en, lname_en) $sortType");
        }
        return $query;
      }

      elseif ($sortBy == 'courses') {
        // Do some work
        return $query;
      }

      return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }

    public function getFullNameAttribute()
    {
      return "{$this->fname_ar} {$this->sname_ar} {$this->tname_ar} {$this->lname_ar}";
    }

    public function getFullNameEnAttribute()
    {
      return "{$this->fname_en} {$this->sname_en} {$this->tname_en} {$this->lname_en}";
    }


    public function courses()
    {
      return $this->morphToMany(Course::class, 'courseable', 'course_user');
    }

    public function tickets()
    {
      return $this->morphMany(TechnicalSupportTicket::class, 'ticketable');
    }

    public function questions()
    {
      return $this->morphToMany(Question::class, 'questionnable', 'question_user');
    }

    public function certificates()
    {
      return $this->morphMany(Certificate::class, 'certificateable');
    }

}
