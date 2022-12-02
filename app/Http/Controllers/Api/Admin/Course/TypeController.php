<?php

namespace App\Http\Controllers\Api\Admin\Course;

use App\Models\Course\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Course\TypeRequest;
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
     * Store a newly created resource in storage.
     *
     * @param  TypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {
        // Upload Image
        if ($request->image) {
            $name = upload_base64_image($request->image, "courses/namings/images");
            $request->merge(['image' => $name]);
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
     * @param  TypeRequest  $request
     * @param  Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, Type $type)
    {
        // Delete the previous image
        if (!$request->image && $type->image) {
            Storage::disk('public')->delete("courses/namings/images/{$type->image}");
        }
        // Upload Image
        if ($request->image && str_starts_with($request->image, 'data:image')) {
            // Delete the previous image
            Storage::disk('public')->delete("courses/namings/images/{$type->image}");
            // Save the new image
            $name = upload_base64_image($request->image, "courses/namings/images");
            $request->merge(['image' => $name]);
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
