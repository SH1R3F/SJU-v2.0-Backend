<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\SubscriberResource;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $subscribers = Subscriber::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total'       => Subscriber::filter($request)->get()->count(),
          'subscribers' => SubscriberResource::collection($subscribers)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          // Account information
          'email' => 'required|email|unique:subscribers,email',
          'password' => 'required|min:6',

          // Personal information
          'fname_ar'        => 'required|min:3',
          'sname_ar'        => 'required|min:3',
          'tname_ar'        => 'required|min:3',
          'lname_ar'        => 'required|min:3',
          'gender'          => 'required|in:0,1',
          'mobile'          => 'required',
          'mobile_key'      => 'required',
          'country'         => 'required',
          'city'            => 'required',
          'nationality'     => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

        // Update
        $subscriber = Subscriber::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'subscriber' => new SubscriberResource($subscriber)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
      return new SubscriberResource($subscriber);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courses(Request $request)
    {

        $courses = [];
        return $courses;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          // Account information
          'subscriberEmail' => 'nullable|email|unique:subscribers,email,' . $subscriber->id,
          'password' => 'nullable|min:6|confirmed',

          // Personal information
          'fname_ar'        => 'nullable|min:3',
          'sname_ar'        => 'nullable|min:3',
          'tname_ar'        => 'nullable|min:3',
          'lname_ar'        => 'nullable|min:3',
          'fname_en'        => 'nullable|min:3',
          'sname_en'        => 'nullable|min:3',
          'tname_en'        => 'nullable|min:3',
          'lname_en'        => 'nullable|min:3',
          'gender'          => 'nullable|in:0,1',
          'birthday_meladi' => 'nullable|date',
          'birthday_hijri'  => 'nullable|date',
          'qualification'   => 'nullable|min:3',
          'major'           => 'nullable|min:3',
          'job_title'       => 'nullable|min:3',
          'employer'        => 'nullable|min:3',
          'country'         => 'nullable',
          'city'            => 'nullable',
          'nationality'     => 'nullable',
          'post_box'        => 'nullable|min:3',
          'post_code'       => 'nullable|min:3',

          // Contact information
          'worktel'         => 'nullable',
          'worktel_ext'     => 'nullable',
          'fax'             => 'nullable',
          'fax_ext'         => 'nullable',
          'mobile'          => 'nullable',
          'mobile_key'      => 'nullable',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        } 

        // Subscriber email
        if ($request->subscriberEmail) {
          $request->merge(['email' => $request->subscriberEmail]);
        }

        // Hash password
        if ($request->password) {
          $request->merge(['password' => Hash::make($request->password)]);
        }

        // Update Avatar
        if ($request->avatar) {
          $base64Image  = explode(";base64,", $request->avatar);
          $explodeImage = explode("image/", $base64Image[0]);
          $imageType    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageType;
          Storage::disk('public')->put("subscribers/{$subscriber->id}/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);

        } else if($subscriber->image) { // If subscriber had avatar then deleted.
          // Delete file from disk
          Storage::disk('public')->delete("subscribers/{$subscriber->id}/images/{$subscriber->image}");
          // Null db value
          $request->merge(['image' => null]);
        }


        // Update
        $subscriber->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        // Delete his files on desk
        Storage::disk('public')->delete("subscribers/{$subscriber->id}");

        // Delete database record
        $subscriber->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
