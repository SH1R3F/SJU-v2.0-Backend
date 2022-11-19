<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['member_id', 'type', 'start_date', 'end_date', 'status'];


    public function member()
    {
      return $this->belongsTo(Member::class);
    }
    
    public function invoices()
    {
      return $this->hasMany(Invoice::class);
    }
}
