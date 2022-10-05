<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = ['file', 'type'];



    public function scopeFilter($query, $request)
    {
      // Filter by type
      $query->where('type', $request->type);
      return $query;
    }

}
