<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
      'code',
      'certificateable_id',
      'certificateable_type',
      'course_id',
      'certificate'
    ];

    public function certificateable()
    {
      return $this->morphTo();
    }

}
