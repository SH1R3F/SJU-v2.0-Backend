<?php

namespace App\Models\Course;

use App\Models\Course\Category;
use App\Models\Course\Template;
use App\Models\Course\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'SN', 'name_ar', 'name_en', 'region', 'type_id', 'category_id', 'gender_id', 'location_id', 'map_link', 'map_latitude', 'map_longitude', 'seats', 'date_from', 'date_to', 'time_from', 'time_to', 'days', 'minutes', 'percentage', 'price', 'images', 'trainer', 'summary', 'content', 'zoom', 'youtube', 'template_id', 'questionnaire_id', 'attendance_duration', 'status' ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_from', 'date_to'];

    
    /**
     * The attributes that should be casts.
     *
     * @var array
     */
    protected $casts = [
      'days' => 'array'  
    ];

    


    public function scopeFilter($query, $request)
    {

      // Filter by SN
      if ($request->sn) {
        $query->where('SN', 'LIKE', "%{$request->sn}%");
      }
      
      // Filter by region
      if ($request->region) {
        $query->where('region', 'LIKE', "%{$request->region}%");
      }

      // Filter by name
      if ($request->name) {
        $query->where('name_ar', 'LIKE', "%{$request->name}%")
              ->orWhere('name_en', 'LIKE', "%{$request->name}%");
      }

      // Filter by day
      if ($request->day) {
        $query->whereDay('date_from', $request->day)
              ->orWhereDay('date_to', $request->day);
      }

      // Filter by month
      if ($request->month) {
        $query->whereMonth('date_from', $request->month)
              ->orWhereMonth('date_to', $request->month);
      }

      // Filter by year
      if ($request->year) {
        $query->whereYear('date_from', $request->year)
              ->orWhereYear('date_to', $request->year);
      }

      // Filter by search
      if ($request->q) {
        $query->where("name_ar", 'LIKE', "%{$request->q}%")
              ->orWhere("name_en", 'LIKE', "%{$request->q}%")
              ->orWhere("content", 'LIKE', "%{$request->q}%")
              ->orWhere("summary", 'LIKE', "%{$request->q}%");
      }
      
      return $query;
    }

    public function scopeSortData($query, $request)
    {
      $sortBy   = $request->sortBy;
      $sortType = $request->sortDesc == 'true' ? 'DESC' : 'ASC';

      if ($sortBy == 'name') {
        if (app()->getLocale() == 'ar') {
          $query->orderBy("name_ar", $sortType);
        } else {
          $query->orderBy("name_en", $sortType);
        }
        return $query;
      }

      if ($sortBy == 'date') {
        $query->orderBy("date_from", $sortType);
        return $query;
      }

      if ($sortBy == 'gender') {
        $query->orderBy("gender_id", $sortType);
        return $query;
      }

      return !empty($sortBy) ? $query->orderBy($sortBy, $sortType) : $query;
    }

    /**
     * Get the type that owns the Course
     *
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get the category that owns the Course
     *
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the gender that owns the Course
     *
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * Get the location that owns the Course
     *
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the template that owns the Course
     *
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the questionnaire that owns the Course
     *
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

}
