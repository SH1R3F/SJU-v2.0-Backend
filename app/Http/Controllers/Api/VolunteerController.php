<?php

namespace App\Http\Controllers\Api;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\VolunteerResource;

class VolunteerController extends Controller
{
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
        'gender'        => 'sometimes|nullable|in:0,1',
        'country'       => 'required|integer',
        'city'          => 'sometimes|nullable|integer',
        'qualification' => 'required|integer',
        'mobile'        => 'nullable|integer',
        'mobile_key'    => 'required|integer'
      ]);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $volunteer = Auth::guard('api-volunteers')->user();
      $volunteer->gender = $request->gender;
      $volunteer->country = $request->country;
      if ($request->country == 0) {
        $volunteer->city = $request->city;
      } else {
        $volunteer->city = null;
      }
      $volunteer->qualification = $request->qualification;
      $volunteer->mobile = $request->mobile;
      $volunteer->mobile_key = $request->mobile_key;
      $volunteer->save();

      return response()->json([
        'message' => __('messages.successful_update'),
        'user'    => new VolunteerResource($volunteer)
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

        $volunteer = Auth::guard('api-volunteers')->user();
        if (Hash::check($request->current_password, $volunteer->password)) {

          $volunteer->password = Hash::make($request->new_password);
          $volunteer->save();
          return response()->json([
            'message' => __('messages.successful_update'),
          ], 200);

        } else {
          return response(['message' => __('messages.password_incorrect')], 422);
        }

    }
}
