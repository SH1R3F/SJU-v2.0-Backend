<?php

namespace App\Models;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
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
      'description_ar',
      'description_en',
      'order'
    ];


    public function scopeFilter($query, $request)
    {

      // Filter by search
      if ($request->q) {
        $query->where("title_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("title_en", 'LIKE', "%{$request->q}%")
              ->orWhere("slug", 'LIKE', "%{$request->q}%")
              ->orWhere("description_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("description_en", 'LIKE', "%{$request->q}%");
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

    public function posts()
    {
      return $this->hasMany(BlogPost::class);
    }

}
