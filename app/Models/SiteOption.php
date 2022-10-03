<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteOption extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'key', 'value' ];
    
    /**
     * The attributes that should be casts.
     *
     * @var array
     */
    protected $casts = [
      'value' => 'array'  
    ];

}
