<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Courseable;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Course\Course;
use App\Models\Course\Question;
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

class Member extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;

    protected $guard = 'api-members';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'national_id',
        'source',
        'date',
        'fname_ar',
        'sname_ar',
        'tname_ar',
        'lname_ar',
        'fname_en',
        'sname_en',
        'tname_en',
        'lname_en',
        'gender',
        'nationality',
        'birthday_hijri',
        'birthday_meladi',
        'qualification',
        'major',
        'journalist_job_title',
        'journalist_employer',
        'newspaper_type',
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
        'city',
        'branch',
        // Experiences and fields [JSON]
        'experiences_and_fields',

        // Files
        'profile_image',
        'national_image',
        'employer_letter',
        'newspaper_license',
        'job_contract',

        // To be updated options
        'updated_personal_information',
        'updated_profile_image',
        'updated_national_image',
        'updated_employer_letter',
        'updated_experiences_and_fields',

        // Membership information
        'membership_number',
        'active',
        'approved',
        // 'last_seen'
        'password',
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
        'experiences_and_fields' => 'array',
        'date' => 'date',
        'birthday_hijri' => 'date',
        'birthday_meladi' => 'date',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
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

      // Filter by approval status
      if (isset($request->approved)) {
        
        $query->where('approved', $request->approved);
        if (in_array($request->approved, [0, null])) {
          $query->orWhereNull('approved');
        }

      }

      // Filter by active status
      if (isset($request->active)) {
        if (is_array($request->active)) {
          $query->whereIn('active', $request->active);
        } else {
          $query->where('active', $request->active);
        }
      }

      // Filter by mobile
      if (isset($request->mobile) && $request->mobile) {
        $query->where('mobile', 'LIKE', "%{$request->mobile}%");
      }
      
      // Filter by email
      if (isset($request->email) && $request->email) {
        $query->where('email', 'LIKE', "%{$request->email}%");
      }

      // Filter by name
      if (isset($request->name) && $request->name) {
        $query->where(DB::raw("CONCAT(`fname_ar`, ' ', `sname_ar`, ' ', `tname_ar`, ' ', `lname_ar` )"), 'LIKE', "%{$request->name}%")
              ->orWhere(DB::raw("CONCAT(`fname_en`, ' ', `sname_en`, ' ', `tname_en`, ' ', `lname_en` )"), 'LIKE', "%{$request->name}%");
      }

      // Filter by national id
      if (isset($request->nationalId) && $request->nationalId) {
        $query->where('national_id', 'LIKE', "%{$request->nationalId}%");
      }

      // Filter by membership number
      if (isset($request->membershipNumber) && $request->membershipNumber) {
        $query->where('membership_number', 'LIKE', "%{$request->membershipNumber}%");
      }

      // Filter by membership type
      if (isset($request->membershipType) && is_numeric($request->membershipType)) {
        $query->whereHas('subscription', function ($query) use ($request) {
          $query->where('type', $request->membershipType);
        });
      }

      // Filter by city
      if (isset($request->city) && is_numeric($request->city)) {
        $query->where('city', $request->city);
      }

      // Filter by year
      if (isset($request->year) && $request->year) {
        $query->whereHas('subscription', function ($query) use ($request) {
          $query->whereYear('start_date', $request->year);
        });
      }

      // Filter by search
      if (isset($request->q) && $request->q) {
        $query->where(DB::raw("CONCAT(`fname_ar`, ' ', `sname_ar`, ' ', `tname_ar`, ' ', `lname_ar` )"), 'LIKE', "%{$request->q}%")
              ->orWhere(DB::raw("CONCAT(`fname_en`, ' ', `sname_en`, ' ', `tname_en`, ' ', `lname_en` )"), 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy == 'member') {
        if (app()->getLocale() == 'ar') {
          $query->orderByRaw("CONCAT(fname_ar, sname_ar, tname_ar, lname_ar) $sortType");
        } else {
          $query->orderByRaw("CONCAT(fname_en, sname_en, tname_en, lname_en) $sortType");
        }
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
      return $this->morphToMany(Course::class, 'courseable', 'course_user')->withPivot('attendance');
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

    public function invoices()
    {
      return $this->hasMany(Invoice::class);
    }

    public function subscription()
    {
      return $this->hasOne(Subscription::class);
    }

    public function notifications()
    {
      return $this->morphMany(Notification::class, 'notifiable');
    }
}
