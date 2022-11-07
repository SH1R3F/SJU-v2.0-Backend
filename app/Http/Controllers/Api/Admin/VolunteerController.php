<?php

namespace App\Http\Controllers\Api\Admin;

use Carbon\Carbon;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\VolunteerResource;
use App\Http\Resources\Admin\Course\CourseResource;

class VolunteerController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:read-volunteer', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-volunteer', [ 'only' => 'store']);
        $this->middleware('permission:update-volunteer', [ 'only' => 'update']);
        $this->middleware('permission:delete-volunteer', [ 'only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $volunteers = Volunteer::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total'       => Volunteer::filter($request)->get()->count(),
          'volunteers' => VolunteerResource::collection($volunteers)
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
          'email' => 'required|email|unique:volunteers,email',
          'password' => 'required|min:6',

          // Personal information
          'national_id'     => 'required|integer',
          'fname_ar'        => 'required|min:3',
          'sname_ar'        => 'required|min:3',
          'tname_ar'        => 'required|min:3',
          'lname_ar'        => 'required|min:3',
          'gender'          => 'required|in:0,1',
          'mobile'          => 'required',
          'mobile_key'      => 'required',
          'country'         => 'required',
          'branch'          => 'required',
          'nationality'     => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

        // Update database
        $volunteer = Volunteer::create($request->all());
        event(new Registered($volunteer));

        return response()->json([
          'message' => __('messages.successful_create'),
          'volunteer' => new VolunteerResource($volunteer)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function show(Volunteer $volunteer)
    {
      return new VolunteerResource($volunteer);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function courses(Request $request, Volunteer $volunteer)
    {
      $courses = $volunteer->courses()->withPivot('attendance')->get();;
      return response()->json([
          'total'   => $courses->count(),
          'courses' => CourseResource::collection($courses)
      ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          // Account information
          'volunteerEmail' => 'nullable|email|unique:volunteers,email,' . $volunteer->id,
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
          'qualification'   => 'nullable|min:3',
          'major'           => 'nullable|min:3',
          'job_title'       => 'nullable|min:3',
          'employer'        => 'nullable|min:3',
          'country'         => 'nullable',
          'branch'            => 'nullable',
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

        // Volunteer email
        if ($request->volunteerEmail) {
          $request->merge(['email' => $request->volunteerEmail]);
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
            Storage::disk('public')->put("volunteers/{$volunteer->id}/images/{$imageName}", $image_base64);
            $request->merge(['image' => $imageName]);
          } else {
            $request->merge(['image' => $volunteer->image]);
          }
        } else if($volunteer->image) { // If volunteer had avatar then deleted.
          // Delete file from disk
          Storage::disk('public')->delete("volunteers/{$volunteer->id}/images/{$volunteer->image}");
          // Null db value
          $request->merge(['image' => null]);
        }


        // Update
        $volunteer->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Volunteer $volunteer)
    {
        // Delete his files on desk
        Storage::disk('public')->deleteDirectory("volunteers/{$volunteer->id}");

        // Delete database record
        $volunteer->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }

    /**
     * Toggle verification of the specified resource from storage.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function toggle(Volunteer $volunteer)
    {
      $volunteer->email_verified_at = $volunteer->email_verified_at ? NULL : Carbon::now();
      $volunteer->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }
}
