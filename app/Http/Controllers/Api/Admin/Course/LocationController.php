<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Location;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\NamingResource;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = Location::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Location::all()->count(),
          'namings' => NamingResource::collection($locations)
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
          'name_ar' => 'required|min:3|unique:locations',
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
          $imageLocation    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageLocation;
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        }

        // Store in database
        $location = Location::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'subscriber' => new NamingResource($location)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
      return new NamingResource($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar' => 'required|min:3|unique:locations,name_ar,' . $location->id,
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
          $imageLocation    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageLocation;
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$location->image}");
          // Save the new image
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        } else if($location->image) {
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$location->image}");
        }

        // Store in database
        $location->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'subscriber' => new NamingResource($location)
        ], 200);
    }

    /**
     * Toggle status of the specified resource in storage.
     *
     * @param  Location  $location
     * @return \Illuminate\Http\Response
     */
    public function toggle(Location $location)
    {
        $location->status = !$location->status;
        $location->save();
        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        // Delete his files on desk
        Storage::disk('public')->delete("courses/namings/images/{$location->image}");

        // Delete database record
        $location->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
  
}
