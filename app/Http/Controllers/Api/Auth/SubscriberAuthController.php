<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\SubscriberResource;

class SubscriberAuthController extends Controller
{

    /**
     * Perform the login action for the subscriber.
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
        $subscriber = Subscriber::where('email', $request->email)->first();
        if (!$subscriber || !Hash::check($request->password, $subscriber->password)) {
          return response(['message' => 'invalid login credentials'], 422);
        }

        if (!$subscriber->hasVerifiedEmail()) {
            $validator->getMessageBag()->add('email', __('messages.email_unverified'));
            return response()->json(array_merge($validator->errors()->toArray(), ['resend' => route('verification.send', ['email' => $subscriber->email, 'type' => 'subscriber'])]), 400);
        }

        // Revoking previous tokens
        $subscriber->tokens()->delete();

        return response([
          'userData'  => new SubscriberResource($subscriber),
          'accessToken' => $subscriber->createToken('accessToken')->plainTextToken
        ]);
    }

    /**
     * register a newly created subscriber in storage.
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
          'hearabout'          => 'required|integer',
          'email'              => 'required|email|unique:subscribers',
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


        // Update database
        $subscriber = Subscriber::create($request->all());

        // Update Avatar
        if (!empty($request->image)) {
          $imageName = time().'.'.$request->image->extension();
          $request->image->move(public_path("storage/subscribers/{$subscriber->id}/images"), $imageName);
          $subscriber->image = "subscribers/{$subscriber->id}/images/{$imageName}";
          $subscriber->save();
        }

        // dispatching the Registered event
        event(new Registered($subscriber));

        // Login and return access token
        return response()->json([
          // 'userData'    => new SubscriberResource($subscriber),
          // 'accessToken' => $subscriber->createToken('accessToken')->plainTextToken,
          'message' => __('messages.successful_create')
        ]);
        
    }

}
