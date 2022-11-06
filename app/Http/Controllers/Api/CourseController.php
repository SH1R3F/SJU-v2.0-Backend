<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
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

      // Update only if registered
      if ($user->courses()->where('course_id', $event->id)->count()) {
        $user->courses()->updateExistingPivot($event, ['attendance' => 1], false);
      }

      return response()->json([
        'message' => __('messages.successful_attend'),
      ], 200);  
    }

}
