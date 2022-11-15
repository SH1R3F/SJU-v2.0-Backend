<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\MemberResource;
use App\Http\Resources\Admin\Course\CourseResource;

class MemberController extends Controller
{


    /**
     * Display the data of the default page for members.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // Upcoming events
      $upcoming = Course::whereIn('status', [1,2,3,4])->where('date_from', '>', Carbon::now())->get();

      // Enrolled events
      $enrolled = Auth::guard('api-members')->user()->courses()->get();

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

      $member = Auth::guard('api-members')->user();
      $member->gender = $request->gender;
      $member->country = $request->country;
      if ($request->country == 0) {
        $member->city = $request->city;
      } else {
        $member->city = null;
      }
      $member->qualification = $request->qualification;
      $member->mobile = $request->mobile;
      $member->mobile_key = $request->mobile_key;
      $member->save();

      return response()->json([
        'message' => __('messages.successful_update'),
        'user'    => new MemberResource($member)
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

        $member = Auth::guard('api-members')->user();
        if (Hash::check($request->current_password, $member->password)) {

          $member->password = Hash::make($request->new_password);
          $member->save();
          return response()->json([
            'message' => __('messages.successful_update'),
          ], 200);

        } else {
          return response(['message' => __('messages.password_incorrect')], 422);
        }

    }
}
