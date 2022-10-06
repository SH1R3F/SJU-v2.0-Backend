<?php

namespace App\Models;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'blog_category_id',
      'title_ar',
      'title_en',
      'post_date',
      'photos',
      'summary_ar',
      'summary_en',
      'content_ar',
      'content_en',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['post_date'];

    
    /**
     * The attributes that should be casts.
     *
     * @var array
     */
    protected $casts = [
      'photos' => 'array',
    ];

    public function scopeFilter($query, $request)
    {

      // Filter by search
      if ($request->q) {
        $query->where("title_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("title_en", 'LIKE', "%{$request->q}%")
              ->orWhere("summary_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("summary_en", 'LIKE', "%{$request->q}%")
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

    public function category()
    {
      return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
