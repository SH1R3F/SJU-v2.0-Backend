<?php

namespace App\Http\Controllers\Api\Admin;

use Carbon\Carbon;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\SubscriberResource;
use App\Http\Controllers\Api\Admin\ExcelController;
use App\Http\Resources\Admin\Course\CourseResource;

class SubscriberController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('permission:read-subscriber', [ 'only' => ['index', 'show', 'export']]);
        $this->middleware('permission:create-subscriber', [ 'only' => 'store']);
        $this->middleware('permission:update-subscriber', [ 'only' => 'update']);
        $this->middleware('permission:delete-subscriber', [ 'only' => 'destroy']);
    }

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
     * Export a listing of the resource to Excel sheet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {

        $subscribers = Subscriber::filter($request)->sortData($request)->get();
        $cells = array(
          'A1' => 'م',
          'B1' => 'اسم المشترك',
          'C1' => 'البريد الإلكتروني',
          'D1' => 'عدد البرامج',
          'E1' => 'حالة المشترك',
        );
        $cells_keys = array(
            'A' => 'counter',
            'B' => 'name',
            'C' => 'email',
            'D' => 'cources',
            'E' => 'status',
        );

        // Build excel cells
        $counter = 2;
        foreach ($subscribers as $subscriber) {
          foreach ($cells_keys as $key => $val) {
            switch ($val) {
              case 'counter':
                $cells[$key . $counter] = $counter - 1;
                break;
                
              case 'email':
                $cells[$key . $counter] = $subscriber->email;
                break;
                
              case 'name':
                $cells[$key . $counter] = $subscriber->fullName;
                break;
                
              case 'courses':
                $cells[$key . $counter] = $subscriber->courses()->count() ? $subscriber->courses()->count() : 0;
                break;
                              
              case 'status':
                $cells[$key . $counter] = config('sju.status')[$subscriber->email_verified_at ? 1 : 0];
                break;
            }
          }
          $counter++;
        }

        // Create the excel file
        return app(ExcelController::class)->create('subscribers', $cells);
        
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
     * @param  Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function courses(Subscriber $subscriber)
    {
      $courses = $subscriber->courses()->withPivot('attendance')->get();;
      return response()->json([
          'total'       => $courses->count(),
          'courses' => CourseResource::collection($courses)
      ], 200);
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
          if (str_starts_with($request->avatar, 'data:image')) {
            $base64Image  = explode(";base64,", $request->avatar);
            $explodeImage = explode("image/", $base64Image[0]);
            $imageType    = $explodeImage[1];
            $image_base64 = base64_decode($base64Image[1]);
            $imageName    = uniqid() . '.'.$imageType;
            Storage::disk('public')->put("subscribers/{$subscriber->id}/images/{$imageName}", $image_base64);
            $request->merge(['image' => "subscribers/{$subscriber->id}/images/{$imageName}"]);
          } else {
            $request->merge(['image' => $subscriber->image]);
          }
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
        Storage::disk('public')->deleteDirectory("subscribers/{$subscriber->id}");

        // Delete database record
        $subscriber->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }

    
    /**
     * Toggle verification of the specified resource from storage.
     *
     * @param  Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function toggle(Subscriber $subscriber)
    {
      $subscriber->email_verified_at = $subscriber->email_verified_at ? NULL : Carbon::now();
      $subscriber->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }
}
