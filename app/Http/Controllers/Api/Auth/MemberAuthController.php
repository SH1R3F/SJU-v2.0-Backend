<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\MemberResource;

class MemberAuthController extends Controller
{

    /**
     * Perform the login action for the member.
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
        $member = Member::where('email', $request->email)->first();
        if (!$member || !Hash::check($request->password, $member->password)) {
          return response(['message' => 'invalid login credentials'], 422);
        }

        if (!$member->hasVerifiedEmail()) {
            $validator->getMessageBag()->add('email', __('messages.email_unverified'));
            return response()->json(array_merge($validator->errors()->toArray(), ['resend' => route('verification.send', ['email' => $member->email, 'type' => 'member'])]), 400);
        }

        // Revoking previous tokens
        $member->tokens()->delete();

        return response([
          'userData'  => new MemberResource($member),
          'accessToken' => $member->createToken('accessToken')->plainTextToken
        ]);
    }

    /**
     * register a newly created member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
          // Contact info
          'contactInfo.mobile' => 'required|integer|regex:/^(5)\d{8}$/',

          // Registration
          'registration.national_id' => 'required|integer|digits:10|unique:members,national_id',

          // Member info
          'memberInfo.source' => 'required',
          'memberInfo.date' => 'required',
          'memberInfo.fname_ar' => 'required',
          'memberInfo.sname_ar' => 'required',
          'memberInfo.tname_ar' => 'required',
          'memberInfo.lname_ar' => 'required',
          'memberInfo.fname_en' => 'required',
          'memberInfo.sname_en' => 'required',
          'memberInfo.tname_en' => 'required',
          'memberInfo.lname_en' => 'required',
          'memberInfo.gender' => 'required',
          'memberInfo.birthday_hijri' => 'required',
          'memberInfo.birthday_meladi' => 'required',
          'memberInfo.nationality' => 'required|integer',
          'memberInfo.qualification' => 'required',
          'memberInfo.major' => 'required',
          'memberInfo.journalist_job_title' => 'required',
          'memberInfo.journalist_employer' => 'required',
          'memberInfo.newspaper_type' => 'required|integer|in:1,2',
          'memberInfo.job_title' => 'required',
          'memberInfo.employer' => 'required',
          'memberInfo.worktel' => 'required|integer',
          'memberInfo.worktel_ext' => 'required|integer',
          'memberInfo.fax' => 'required|integer',
          'memberInfo.fax_ext' => 'required|integer',
          'memberInfo.post_box' => 'required|integer',
          'memberInfo.post_code' => 'required|integer',
          'memberInfo.city' => 'required',
          'memberInfo.email' => 'required|email|unique:members,email',

          // Login info
          'loginInfo.password' => 'required|min:6',

          // Membership subscription
          'membershipTypes.membership_type' => 'required|integer',
          'membershipTypes.branch' => 'required|integer',
          'membershipTypes.delivery_method' => 'nullable|integer|in:1,2',
          'membershipTypes.delivery_address' => 'nullable',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $data = [];
        foreach ($request->all() as $key => $fields) {
          foreach ($fields as $key => $field) {
            if ($key === 'mobile') {
              $field = '966' . $field;
            }
            $data[$key] = $field;
          }
        }
        // Hash password
        $data['password'] = Hash::make($request->password);


        // Update database
        $member = Member::create($data);

        // Update Avatar
        // if (!empty($request->image)) {
        //   $imageName = time().'.'.$request->image->extension();
        //   $request->image->move(public_path("storage/members/{$member->id}/images"), $imageName);
        //   $member->image = "members/{$member->id}/images/{$imageName}";
        //   $member->save();
        // }

        // dispatching the Registered event
        event(new Registered($member));

        // Login and return access token
        return response()->json([
          // 'userData'    => new MemberResource($member),
          // 'accessToken' => $member->createToken('accessToken')->plainTextToken,
          'message' => __('messages.successful_register')
        ]);
        
    }

}
