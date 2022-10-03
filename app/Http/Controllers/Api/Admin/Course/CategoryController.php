<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\NamingResource;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Category::all()->count(),
          'namings' => NamingResource::collection($categories)
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
          'name_ar' => 'required|min:3|unique:categories',
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
          $imageCategory    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageCategory;
          Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
          $request->merge(['image' => $imageName]);
        }

        // Store in database
        $category = Category::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'subscriber' => new NamingResource($category)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
      return new NamingResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar' => 'required|min:3|unique:categories,name_ar,' . $category->id,
          'name_en' => 'nullable|min:3',
          'description_ar' => 'nullable|min:3',
          'description_en' => 'nullable|min:3'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Image
        if ($request->image) {

          if (str_starts_with($request->image, 'data:image')) {
            $base64Image  = explode(";base64,", $request->image);
            $explodeImage = explode("image/", $base64Image[0]);
            $imageCategory    = $explodeImage[1];
            $image_base64 = base64_decode($base64Image[1]);
            $imageName    = uniqid() . '.'.$imageCategory;
            // Delete the previous image
            Storage::disk('public')->delete("courses/namings/images/{$category->image}");
            // Save the new image
            Storage::disk('public')->put("courses/namings/images/{$imageName}", $image_base64);
            $request->merge(['image' => $imageName]);
          } else {
            $request->merge(['image' => $category->image]);
          }
          
        } else if($category->image) {
          // Delete the previous image
          Storage::disk('public')->delete("courses/namings/images/{$category->image}");
        }


        // Store in database
        $category->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'subscriber' => new NamingResource($category)
        ], 200);
    }

    /**
     * Toggle status of the specified resource in storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function toggle(Category $category)
    {
        $category->status = !$category->status;
        $category->save();
        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Delete his files on desk
        Storage::disk('public')->deleteDirectory("courses/namings/images/{$category->image}");

        // Delete database record
        $category->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
  
}
