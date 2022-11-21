<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'note', 'read', 'notifiable_id', 'notifiable_type'];


    public function notifiable()
    {
      return $this->morphTo();
    }

}
