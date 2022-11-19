<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Subscription;
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

    public function member()
    {
      return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
      return $this->belongsTo(Subscription::class);
    }
}
