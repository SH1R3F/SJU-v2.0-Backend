<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Location;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Course\LocationRequest;
use App\Http\Resources\Admin\Course\NamingResource;

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
     * Store a newly created resource in storage.
     *
     * @param  LocationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        // Upload Image
        if ($request->image) {
            $name = upload_base64_image($request->image, "courses/namings/images");
            $request->merge(['image' => $name]);
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
     * @param  LocationRequest  $request
     * @param  Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, Location $location)
    {

        // Delete the previous image
        if (!$request->image && $location->image) {
            Storage::disk('public')->delete("courses/namings/images/{$location->image}");
        }

        // Upload Image
        if ($request->image) {
            if (str_starts_with($request->image, 'data:image')) {
                // Delete the previous image
                Storage::disk('public')->delete("courses/namings/images/{$location->image}");
                // Save the new image
                $name = upload_base64_image($request->image, "courses/namings/images");
                $request->merge(['image' => $name]);
            }
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
        Storage::disk('public')->deleteDirectory("courses/namings/images/{$location->image}");

        // Delete database record
        $location->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ], 200);
    }
}
