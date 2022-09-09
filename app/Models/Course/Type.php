<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
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
      'description_ar',
      'description_en',
      'image',
      'status'
    ];


    public function scopeFilter($query, $request)
    {

      // Filter by search
      if ($request->q) {
        $query->where("name_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("name_en", 'LIKE', "%{$request->q}%")
              ->orWhere("description_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("description_en", 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy == 'name') {
        $query->orderBy("name_ar $sortType");
        return $query;
      }

      return $query->orderBy($sortBy, $sortType);
    }
}
