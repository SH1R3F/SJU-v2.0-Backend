<?php

namespace App\Models\Course;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Course\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
      'question', 'type', 'answer1', 'color1', 'answer2', 'color2', 'answer3', 'color3', 'answer4', 'color4', 'order'
    ];


    /**
     * Get the questionnaire that owns the question
     *
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Many to many to the volunteers
     *
     */
    public function volunteers()
    {
      return $this->morphedByMany(Volunteer::class, 'questionnable', 'question_user');
    }

    /**
     * Many to many to the members
     *
     */
    public function members()
    {
      return $this->morphedByMany(Member::class, 'questionnable', 'question_user');
    }

    /**
     * Many to many to the subscriber
     *
     */
    public function subscriber()
    {
      return $this->morphedByMany(subscriber::class, 'questionnable', 'question_user');
    }


}
