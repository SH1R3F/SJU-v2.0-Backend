<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Template;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\TemplateResource;

class TemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-template', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-template', [ 'only' => 'store']);
        $this->middleware('permission:update-template', [ 'only' => 'update']);
        $this->middleware('permission:delete-template', [ 'only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $templates = Template::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Template::all()->count(),
          'templates' => TemplateResource::collection($templates)
        ]);
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
          'name'         => 'required|min:3',
          'templatefile' => 'required|file',
          'language'     => 'required',
          'layout'       => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Template file
        if ($request->hasFile('templatefile')) {
          // Save the new file
          $file = $request->file('templatefile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("/courses/templates/", $name, 'public');
          $request->merge(['file' => $name]);
        }

        // Store in database
        $template = Template::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'template' => new TemplateResource($template)
        ], 200);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return new TemplateResource($template);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name'         => 'required|min:3',
          'language'     => 'required',
          'layout'       => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Image
        if ($request->hasFile('templatefile')) {
      
          // Delete the previous file
          Storage::disk('public')->delete("courses/templates/{$template->file}");
 
          // Save the new file
          $file = $request->file('templatefile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("/courses/templates/", $name, 'public');
          $request->merge(['file' => $name]);

          // Delete the previous if exists
          Storage::disk('public')->delete("courses/templates/{$template->file}");
        }

        // Store in database
        $template->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'template' => new TemplateResource($template)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        // Delete files on desk
        Storage::disk('public')->deleteDirectory("courses/templates/{$template->file}");

        // Delete database record
        $template->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);

    }
}
