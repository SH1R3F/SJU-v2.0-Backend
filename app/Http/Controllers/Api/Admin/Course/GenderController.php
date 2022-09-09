<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Gender;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Course\NamingResource;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $genders = Gender::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Gender::all()->count(),
          'namings' => NamingResource::collection($genders)
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
          'name_ar' => 'required|min:3|unique:genders',
          'name_en' => 'nullable|min:3',
          'description_ar' => 'nullable|min:3',
          'description_en' => 'nullable|min:3'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Image
        if ($request->image) {
          $base64Image  = explode(";base64,", $request->image);
          $explodeImage = explode("image/", $base64Image[0]);
          $imageGender    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageGender;
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        }

        // Store in database
        $gender = Gender::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'subscriber' => new NamingResource($gender)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show(Gender $gender)
    {
      return new NamingResource($gender);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gender $gender)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar' => 'required|min:3|unique:genders,name_ar,' . $gender->id,
          'name_en' => 'nullable|min:3',
          'description_ar' => 'nullable|min:3',
          'description_en' => 'nullable|min:3'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Image
        if ($request->image) {
          $base64Image  = explode(";base64,", $request->image);
          $explodeImage = explode("image/", $base64Image[0]);
          $imageGender    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageGender;
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$gender->image}");
          // Save the new image
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        } else if($gender->image) {
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$gender->image}");
        }

        // Store in database
        $gender->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'subscriber' => new NamingResource($gender)
        ], 200);
    }

    /**
     * Toggle status of the specified resource in storage.
     *
     * @param  Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function toggle(Gender $gender)
    {
        $gender->status = !$gender->status;
        $gender->save();
        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gender $gender)
    {
        // Delete his files on desk
        Storage::disk('public')->delete("courses/namings/images/{$gender->image}");

        // Delete database record
        $gender->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
