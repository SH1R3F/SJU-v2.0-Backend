<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

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
        // Experiences and fields [JSON]
        'experiences_and_fields',

        // Files
        'profile_image',
        'national_image',
        'employer_letter',

        // To be updated options
        'updated_personal_information',
        'updated_profile_image',
        'updated_national_image',
        'updated_employer_letter',
        'updated_experiences_and_fields',

        // Membership information
        'membership_number',
        'membership_type',
        'membership_start_date',
        'membership_end_date',
        'invoice_id',
        'invoice_status',
        'status',
        'password'
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


    public function scopeFilter($query, $request)
    {

      // Filter by status
      // $query->where('status', $request->status);

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

      // Filter by national id
      if ($request->nationalId) {
        $query->where('national_id', 'LIKE', "%{$request->nationalId}%");
      }

      // Filter by membership number
      if ($request->membershipNumber) {
        $query->where('membership_number', 'LIKE', "%{$request->membershipNumber}%");
      }

      // Filter by membership type
      if (is_numeric($request->membershipType)) {
        $query->where('membership_type', $request->membershipType);
      }

      // Filter by city
      if (is_numeric($request->city)) {
        $query->where('city', $request->city);
      }

      // Filter by year
      if ($request->year) {
        $query->whereYear('membership_start_date', $request->year);
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

      if ($sortBy == 'member') {
        if (app()->getLocale() == 'ar') {
          $query->orderByRaw("CONCAT(fname_ar, sname_ar, tname_ar, lname_ar) $sortType");
        } else {
          $query->orderByRaw("CONCAT(fname_en, sname_en, tname_en, lname_en) $sortType");
        }
        return $query;
      }

      return $query->orderBy($sortBy, $sortType);
    }

}
