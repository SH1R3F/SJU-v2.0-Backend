<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Resources\Admin\Course\CourseResource;

class CourseController extends Controller
{

    use LoggedInUser;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $page = $request->page ? intval($request->page) : 1;
      $events = Course::filter($request)->whereIn('status', [1, 2, 3, 4])->orderBy('id', 'DESC')->offset($request->perPage * $page)->paginate($request->perPage);
      return CourseResource::collection($events);
    }


    /**
     * Display the specified resource.
     *
     * @param  Course  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Course $event)
    {

      // Only if course is still active
      if (!in_array($event->status, [1, 2, 3, 4])) {
        return abort(404);
      }

      if ($this->loggedInUser()['user']) {
        $course = $this->loggedInUser()['user']->courses()->where('id', $event->id);
        $event->registered = ($course->count() ? $course->first()->pivot : false);
      }
      
      $events = $event->where('id', '!=', $event->id)->whereIn('status', [1,2,3,4])->where('date_from', '>', Carbon::now())->take(4)->get();
      return response()->json([
        'event'  => new CourseResource($event),
        'events' => CourseResource::collection($events)
      ]);
    }


    /**
     * Enroll authenticated user into a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Course  $event
     * @return \Illuminate\Http\Response
     */
    public function enroll(Request $request, Course $event)
    {

      // Only if course is still active
      if (!in_array($event->status, [1, 2, 3, 4])) {
        return response()->json([
          'message' => __('messages.event_unavailable'),
        ], 422); 
      }

      $user = $this->loggedInUser()['user'];

      // Update only if not registered
      if (!$user->courses()->where('course_id', $event->id)->count()) {
        $user->courses()->attach($event->id);
      }

      return response()->json([
        'message' => __('messages.successful_register'),
      ], 200);  
    }

    /**
     * Attend authenticated user into a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Course  $event
     * @return \Illuminate\Http\Response
     */
    public function attend(Request $request, Course $event)
    {
      $user = $this->loggedInUser()['user'];


      // Only if course is still active and still allowed to attend
      if (!in_array($event->status, [1, 2, 3, 4])) {
        return response()->json([
          'message' => __('messages.event_unavailable'),
        ], 422); 
      }

      // If attendance duration has already passed
      $date     = explode(' ', $event->date_to)[0];
      $end_date = Carbon::parse("{$date} {$event->time_to}")->addMinutes($event->attendance_duration);

      if (Carbon::now()->gt($end_date)) {
        return response()->json([
          'message' => __('messages.attendance_unavailable'),
        ], 422); 
      }


      // Update only if registered
      if ($user->courses()->where('course_id', $event->id)->count()) {
        $user->courses()->updateExistingPivot($event, ['attendance' => 1], false);
      }

      return response()->json([
        'message' => __('messages.successful_attend'),
      ], 200);  
    }


    /**
     * Get the certificate of the course for the authenticated user.
     * Only if he has passed the questionnaire if exists
     * Or return questionnaire if didn't
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Course  $event
     * @return \Illuminate\Http\Response
     */
    public function certificate(Request $request, Course $event)
    {
      $user = $this->loggedInUser()['user'];
      
      $registered = $user->courses()->where('course_id', $event->id)->first();

      // ONLY IF has registered and attended the event
      if (!$registered || !$registered->pivot->attendance) {
        return response()->json([
          'message' => __('messages.certificate_notallowed')
        ], 422);
      }
      // Registered and attended


      // If it has questionnaire. FIRST OF ALL. he has to complete it
      if ($event->questionnaire) { 
        $solved = $user->questions()->where('questionnaire_id', $event->questionnaire_id)->count();

        // User didn't solve all of the questions
        if ($solved != $event->questionnaire->questions()->count()) { 
          return response()->json([
            'id'   => $event->questionnaire_id,
            'type' => 'questionnaire'
          ]);
        }
      }


      // Make the user certificate !
      if ($event->template) {
        return app(CertificateController::class)->show($event);
      }


    }
    

}
