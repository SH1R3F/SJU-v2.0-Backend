<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'title_ar',
      'title_en',
      'slug',
      'content_ar',
      'content_en',
    ];

    public function scopeFilter($query, $request)
    {

      // Filter by search
      if ($request->q) {
        $query->where("title_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("title_en", 'LIKE', "%{$request->q}%")
              ->orWhere("content_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("content_en", 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy === 'title') {
        if (app()->getLocale() == 'ar') {
          $query->orderBy("title_ar", $sortBy);
        } else {
          $query->orderBy("title_en", $sortBy);
        }
        return $query;
      }

      return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }

}
