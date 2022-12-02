<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Gender;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Course\GenderRequest;
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
     * @param  GenderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenderRequest $request)
    {
        // Upload Image
        if ($request->image) {
            $name = upload_base64_image($request->image, "courses/namings/images");
            $request->merge(['image' => $name]);
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
     * @param  GenderRequest  $request
     * @param  Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(GenderRequest $request, Gender $gender)
    {

        if (!$request->image && $gender->image) { // Delete the previous image
            Storage::disk('public')->delete("courses/namings/images/{$gender->image}");
        }

        // Upload Image
        if ($request->image) {
            if (str_starts_with($request->image, 'data:image')) {
                // Delete the previous image
                Storage::disk('public')->delete("courses/namings/images/{$gender->image}");
                $name = upload_base64_image($request->image, "courses/namings/images");
                $request->merge(['image' => $name]);
            }
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
        Storage::disk('public')->deleteDirectory("courses/namings/images/{$gender->image}");

        // Delete database record
        $gender->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ], 200);
    }
}
