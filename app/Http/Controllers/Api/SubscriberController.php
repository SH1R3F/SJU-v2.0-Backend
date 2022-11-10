<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\SubscriberResource;
use App\Http\Resources\Admin\Course\CourseResource;

class SubscriberController extends Controller
{


    /**
     * Display the data of the default page for subscribers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // Upcoming events
      $upcoming = Course::whereIn('status', [1,2,3,4])->where('date_from', '>', Carbon::now())->get();

      // Enrolled events
      $enrolled = Auth::guard('api-subscribers')->user()->courses()->get();

      return response()->json([
        'upcomingEvents' => CourseResource::collection($upcoming),
        'enrolledEvents' => CourseResource::collection($enrolled)
      ]);
    
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      // Validation
      $validator = Validator::make($request->all(), [
        'gender'        => 'nullable|in:0,1',
        'country'       => 'required|integer',
        'city'          => 'nullable|integer',
        'qualification' => 'required|integer',
        'mobile'        => 'nullable|integer',
        'mobile_key'    => 'required|integer'
      ]);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $subscriber = Auth::guard('api-subscribers')->user();
      $subscriber->gender = $request->gender;
      $subscriber->country = $request->country;
      if ($request->country == 0) {
        $subscriber->city = $request->city;
      } else {
        $subscriber->city = null;
      }
      $subscriber->qualification = $request->qualification;
      $subscriber->mobile = $request->mobile;
      $subscriber->mobile_key = $request->mobile_key;
      $subscriber->save();

      return response()->json([
        'message' => __('messages.successful_update'),
        'user'    => new SubscriberResource($subscriber)
      ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
          'current_password' => 'required|min:6',
          'new_password'     => 'required|min:6',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $subscriber = Auth::guard('api-subscribers')->user();
        if (Hash::check($request->current_password, $subscriber->password)) {

          $subscriber->password = Hash::make($request->new_password);
          $subscriber->save();
          return response()->json([
            'message' => __('messages.successful_update'),
          ], 200);

        } else {
          return response(['message' => __('messages.password_incorrect')], 422);
        }

    }
}
