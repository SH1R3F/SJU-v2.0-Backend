<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\VolunteerResource;

class VolunteerAuthController extends Controller
{

    /**
     * Perform the login action for the volunteer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'email'    => 'required|email',
          'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Check login
        $volunteer = Volunteer::where('email', $request->email)->first();
        if (!$volunteer || !Hash::check($request->password, $volunteer->password)) {
          return response(['message' => 'invalid login credentials'], 422);
        }

        // Revoking previous tokens
        $volunteer->tokens()->delete();

        return response([
          'userData'  => new VolunteerResource($volunteer),
          'accessToken' => $volunteer->createToken('accessToken')->plainTextToken
        ]);
    }

    /**
     * register a newly created volunteer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',

          // Name Fields
          'fname_ar' => 'required|min:3|max:50',
          'sname_ar' => 'required|min:3|max:50',
          'tname_ar' => 'required|min:3|max:50',
          'lname_ar' => 'required|min:3|max:50',
          'fname_en' => 'required|min:3|max:50',
          'sname_en' => 'required|min:3|max:50',
          'tname_en' => 'required|min:3|max:50',
          'lname_en' => 'required|min:3|max:50',

          // Personal Information
          'gender'             => 'required|in:0,1',
          'country'            => 'required|integer',
          'nationality'        => 'required|integer',
          'qualification'      => 'required|integer',
          'national_id'        => 'required|integer|unique:volunteers',
          'marital_status'     => 'required|string',
          'adminstrative_area' => 'required|string',
          'governorate'        => 'required|string',
          'national_address'   => 'required|string',
          'job_title'          => 'required|string',
          'address'            => 'required|string',
          'fields'             => 'required',
          'education'          => 'required|string',
          'experiences'        => 'required|string',
          'branch'             => 'required|integer',
          'hearabout'          => 'required|integer',
          'email'              => 'required|email|unique:volunteers',
          'mobile'             => 'required|integer',
          'mobile_key'         => 'required|integer',
          'password'           => 'required|min:6',
          'password_confirm'   => 'required|min:6',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);


        // Fields
        $request->merge(['fields' => explode(',', $request->fields)]);


        // Update database
        $volunteer = Volunteer::create($request->all());

        // Update Avatar
        if ($request->image) {
          $imageName = time().'.'.$request->image->extension();
          $request->image->move(public_path("storage/volunteers/{$volunteer->id}/images"), $imageName);
          $volunteer->image = "volunteers/{$volunteer->id}/images/{$imageName}";
          $volunteer->save();
        }

        // Login and return access token
        return response()->json([
          'userData'    => new VolunteerResource($volunteer),
          'accessToken' => $volunteer->createToken('accessToken')->plainTextToken
        ]);
        
    }

}
