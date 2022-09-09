<?php

namespace App\Http\Controllers\Api\Admin\Course;

use App\Models\Course\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\NamingResource;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $types = Type::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Type::all()->count(),
          'namings' => NamingResource::collection($types)
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
          'name_ar' => 'required|min:3|unique:types',
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
          $imageType    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageType;
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        }

        // Store in database
        $type = Type::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'subscriber' => new NamingResource($type)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
      return new NamingResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar' => 'required|min:3|unique:types,name_ar,' . $type->id,
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
          $imageType    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageType;
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$type->image}");
          // Save the new image
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        } else if($type->image) {
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$type->image}");
        }

        // Store in database
        $type->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'subscriber' => new NamingResource($type)
        ], 200);
    }

    /**
     * Toggle status of the specified resource in storage.
     *
     * @param  Type  $type
     * @return \Illuminate\Http\Response
     */
    public function toggle(Type $type)
    {
        $type->status = !$type->status;
        $type->save();
        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        // Delete his files on desk
        Storage::disk('public')->delete("courses/namings/images/{$type->image}");

        // Delete database record
        $type->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
