<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Subscriber;
use App\Models\TechnicalSupportChat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalSupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
      'title',
      'status',
      'ticketable_id',
      'ticketable_type',
    ];


    public function scopeFilter($query, $request)
    {

      // Filter by type
      if ($request->type !== 'all') {
        $type = $request->type === 'volunteer' ? Volunteer::class : ($request->type === 'subscriber' ? Subscriber::class : Member::class);
        $query->where('ticketable_type', $type);
      }

      // Filter by status
      if ($request->status !== 'all') {
        $query->where('status', $request->status);
      }

      // Filter by title
      if ($request->title) {
        $query->where('title', 'LIKE', "%{$request->title}%");
      }

      // Filter by search
      if ($request->q) {
        $query->where('title', 'LIKE', "%{$request->q}%");
      }

      // Filter by: ticketable name, ticketable mobile, ticketable email
      
      return $query;
    }

    public function chats()
    {
      return $this->hasMany(TechnicalSupportChat::class);
    }

    public function ticketable()
    {
      return $this->morphTo();
    }
}
