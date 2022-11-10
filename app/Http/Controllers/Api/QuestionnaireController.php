<?php

namespace App\Http\Controllers\Api;

use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Models\Course\Questionnaire;
use App\Http\Resources\Admin\Course\QuestionnaireResource;

class QuestionnaireController extends Controller
{
    
    use LoggedInUser;

    /**
     * Display the specified resource.
     *
     * @param  Questionnaire  $questionnaire
     * @param  Course  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Course $event, Questionnaire $questionnaire)
    {
        $user = $this->loggedInUser()['user'];

        // First.. Authenticated user has to be registered and attended to the event of this questionnaire!
        $registered = $user->courses()->where('course_id', $event->id)->first();
        if (!$registered || !$registered->pivot->attendance) {
          return response()->json([
            'message' => __('messages.questionnaire_notallowed')
          ], 422);
        }

        // Make sure he didn't have this survey before
        $solved = $user->questions()->where('questionnaire_id', $event->questionnaire_id)->count();
        if ($solved === $event->questionnaire->questions()->count()) {
          return response()->json([
            'message' => __('messages.questionnaire_solved')
          ], 422);
        }

        $answers = $user->questions()->where('questionnaire_id', $questionnaire->id)->get();
        return response()->json([
          'questionnaire' => new QuestionnaireResource($questionnaire),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Course  $event
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $event, Questionnaire $questionnaire)
    {
        $user = $this->loggedInUser()['user'];

        // First.. Authenticated user has to be registered and attended to the event of this questionnaire!
        $registered = $user->courses()->where('course_id', $event->id)->first();
        if (!$registered || !$registered->pivot->attendance) {
          return response()->json([
            'message' => __('messages.questionnaire_notallowed')
          ], 422);
        }

        // Make sure he didn't have this survey before
        $solved = $user->questions()->where('questionnaire_id', $event->questionnaire_id)->count();
        if ($solved != $event->questionnaire->questions()->count()) { 
          // Save these question answers
          foreach ($request->questionnaire as $key => $answer) {
            $id = explode('-', $key)[1];
            foreach ($answer as $type => $val) {
              if (strpos($type, 'textarea') === false) {
                // Choice
                $user->questions()->attach($id, ['choice' => $val]);
              } else {
                // Answer
                $user->questions()->attach($id, ['answer' => $val]);
              }
            }
          }
        }

        return response()->json([
          'message' => __('messages.thankyou')
        ]);
        
    }

}
