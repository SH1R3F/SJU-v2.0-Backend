<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Menu;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;

class BlogCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = BlogCategory::orderBy('order', 'ASC')->get();
        return response()->json([
            'total'      => BlogCategory::count(),
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryRequest $request)
    {
<<<<<<< HEAD
        $validator = Validator::make($request->all(), [
            'title_ar'          => 'required',
            'title_en'          => 'required',
            'slug'              => 'required|alpha_dash|unique:blog_categories',
            'description_ar'    => 'required',
            'description_en'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = BlogCategory::create($request->all());
=======
        BlogCategory::create($request->validated());
>>>>>>> refactor/SingleResponsibility
        return response()->json([
            'message' => __('messages.successful_create')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show(BlogCategory $category)
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogCategoryRequest  $request
     * @param  BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryRequest $request, BlogCategory $category)
    {
<<<<<<< HEAD
        $validator = Validator::make($request->all(), [
            'title_ar'          => 'required',
            'title_en'          => 'required',
            'slug'              => 'required|alpha_dash|unique:blog_categories,slug,' . $category->id,
            'description_ar'    => 'required',
            'description_en'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category->update($request->all());

=======
        $category->update($request->validated());
>>>>>>> refactor/SingleResponsibility
        return response()->json([
            'message' => __('messages.successful_update')
        ]);
    }


    /**
     * Update the the order resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        foreach ($request->categories as $k => $category) {
            Menu::where('id', $category['id'])->update(['order' => $k + 1]);
        }

        return response()->json([
            'message' => __('messages.successful_update')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BlogCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ]);
    }
}
