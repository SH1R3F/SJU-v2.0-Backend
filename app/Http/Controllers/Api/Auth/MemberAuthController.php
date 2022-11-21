<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\SiteOption;
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
          'national_id' => 'required',
          'password'    => 'required|min:6'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Check login
        $member = Member::where('national_id', $request->national_id)->first();
        if (!$member || !Hash::check($request->password, $member->password)) {
          return response(['message' => 'invalid login credentials'], 422);
        }

        // IF account is locked by admins!
        if ($member->active === 0) {
            $validator->getMessageBag()->add('national_id', __('messages.account_locked'));
            return response()->json(array_merge($validator->errors()->toArray()), 400);
        }

        // Email verification check
        if (!$member->hasVerifiedEmail() && false) { // && False as it's Not required at the moment
            $validator->getMessageBag()->add('national_id', __('messages.email_unverified'));
            return response()->json(array_merge($validator->errors()->toArray(), ['resend' => route('verification.send', ['email' => $member->email, 'type' => 'member'])]), 400);
        }

        // Mobile verification check
        if (!$member->mobile_verified_at) {
            $validator->getMessageBag()->add('national_id', __('messages.mobile_unverified'));
            $mobile = substr($member->mobile, 3);
            return response()->json(array_merge($validator->errors()->toArray(), [
              'resend' => "/members/auth/verify?mobile={$mobile}",
              'status' => 'verify_mobile'
            ]), 400);
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
          'memberInfo.nationality' => 'required',
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

        // Create a subscription for this member too !
        $member->subscription()->create([
          'type'   => $data['membership_type'],
        ]);

        // Send mobile verification code
        if (config('app.env') === 'production') {
          $code = rand(1000,9999);
          $result = sendSMS("966{$mobile}", __('messages.verification_code_is', ['mobile' => "966{$mobile}", 'code' => $code]));
        } else { // Save resources in development
          $code = 1234;
          $result = true;
        }
        // Save code to db
        $member->mobile_code = $code;
        $member->save();

        
        // dispatching the Registered event
        event(new Registered($member));

        // Login and return access token
        return response()->json([
          // 'userData'    => new MemberResource($member),
          // 'accessToken' => $member->createToken('accessToken')->plainTextToken,
          'message' => __('messages.successful_register')
        ]);
        
    }

    /**
     * Resending the verification code to member's mobile.
     *
     * @param  String  $mobile
     * @return \Illuminate\Http\Response
     */
    public function sendVerificationCode($mobile)
    {
        // Validate mobile
        $valid = preg_match('/^(5)\d{8}$/', $mobile);
        if (!$valid) {
          return response()->json(['message' => __('messages.mobile_invalid')], 422);
        }

        // Validate members's mobile
        $member = Member::where('mobile', "966{$mobile}")->first();
        if (!$member) {
          return response()->json(['message' => __('messages.mo_member_with_mobile')], 422);
        }

        // Member has not to be verified already
        if ($member->mobile_verified_at) {
          return response()->json(['message' => __('messages.already_verified')], 422);
        }

        // Send verification code
        if (config('app.env') === 'production') {
          $code = rand(1000,9999);
          $result = sendSMS("966{$mobile}", __('messages.verification_code_is', ['mobile' => "966{$mobile}", 'code' => $code]));
        } else { // Save resources in development
          $code = 1234;
          $result = true;
        }
        if ($result === true) { // Because it might be 1
          // Save code to db
          $member->mobile_code = $code;
          $member->save();
          // Return success message
          return response()->json([
            'message' => __('messages.verification_sent')
          ]);
        } else {
          return response()->json([
            'message' => __('messages.verification_not_sent', 422)
          ]);
        }
    }

    /**
     * Verifying mobile number of a member.
     *
     * @param  String  $mobile
     * @param  String  $code
     * @return \Illuminate\Http\Response
     */
    public function verifyMobile($mobile, $code) 
    {
        // Validate mobile
        $valid = preg_match('/^(5)\d{8}$/', $mobile);
        if (!$valid) {
          return response()->json(['message' => __('messages.mobile_invalid')], 422);
        }

        // Validate members's mobile
        $member = Member::where('mobile', "966{$mobile}")->first();
        if (!$member) {
          return response()->json(['message' => __('messages.mo_member_with_mobile')], 422);
        }

        // Member has not to be verified already
        if ($member->mobile_verified_at) {
          return response()->json(['message' => __('messages.already_verified')], 422);
        }

        // Validate code 
        if ($member->mobile_code !== $code) {
          return response()->json(['message' => __('messages.code_invalid')], 422);
        }

        // Otherwise. Verify
        $member->mobile_verified_at = Carbon::now();
        $member->save();

        return response()->json([
          'message' => __('messages.verification_done')
        ]);
    }

}
