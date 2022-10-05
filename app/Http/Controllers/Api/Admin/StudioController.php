<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Studio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\StudioResource;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studio = Studio::filter($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total' => Studio::filter($request)->get()->count(),
          'items' => StudioResource::collection($studio),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type)
    {
        if (!in_array($type, ['photo', 'video'])) return;

        $validator = Validator::make($request->all(), [
          'studioFile' => 'required|file'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Save the new file
        $file = $request->file('studioFile');
        $name = uniqid() . '.' . $file->extension();
        $file->storeAs("/studio/$type/", $name, 'public');
        $request->merge(['file' => $name]);
        $request->merge(['type' => $type]);

        // Store in database
        Studio::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Studio  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studio $item)
    {
        $item->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
