<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Course\CategoryRequest;
use App\Http\Resources\Admin\Course\NamingResource;

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
     * @param  CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        // Upload Image
        if ($request->image) {
            $name = upload_base64_image($request->image, "courses/namings/images");
            $request->merge(['image' => $name]);
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
     * @param  CategoryRequest  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // Delete the previous image
        if (!$request->image && $category->image) {
            Storage::disk('public')->delete("courses/namings/images/{$category->image}");
        }

        // Upload Image
        if ($request->image) {
            if (str_starts_with($request->image, 'data:image')) {
                // Delete the previous image
                Storage::disk('public')->delete("courses/namings/images/{$category->image}");
                $name = upload_base64_image($request->image, "courses/namings/images");
                $request->merge(['image' => $name]);
            }
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
