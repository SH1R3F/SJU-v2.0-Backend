<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['invoice_number', 'cart_ref', 'member_id', 'subscription_id', 'order_ref', 'amount', 'member_data', 'subscription_data', 'order_data', 'payment_method', 'status'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'member_data'       => 'array',
        'subscription_data' => 'array',
        'order_data'        => 'array',
    ];

    

    public function scopeFilter($query, $request)
    {

      // Filter by search
      if (isset($request->q) && $request->q) {
        $query->whereHas('member', function ($query) use ($request) {
          return $query->where(DB::raw("CONCAT(`fname_ar`, ' ', `sname_ar`, ' ', `tname_ar`, ' ', `lname_ar` )"), 'LIKE', "%{$request->q}%")
              ->orWhere(DB::raw("CONCAT(`fname_en`, ' ', `sname_en`, ' ', `tname_en`, ' ', `lname_en` )"), 'LIKE', "%{$request->q}%");
        });
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }

    public function member()
    {
      return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
      return $this->belongsTo(Subscription::class);
    }
}
