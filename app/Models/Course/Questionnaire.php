<?php

namespace App\Models\Course;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'name_ar',
      'name_en',
      'status'
    ];

    public function scopeFilter($query, $request)
    {

      // Filter by search
      if ($request->q) {
        $query->where("name", 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }

    
    /**
     * Get all of the courses for the Type
     *
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
