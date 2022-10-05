<?php

namespace App\Models;

use App\Models\TechnicalSupportTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalSupportChat extends Model
{
    use HasFactory;

    protected $fillable = [
      'technical_support_ticket_id',
      'message',
      'attachment',
      'sender'
    ];

    public function ticket()
    {
      return $this->belongsTo(TechnicalSupportTicket::class);
    }
}
