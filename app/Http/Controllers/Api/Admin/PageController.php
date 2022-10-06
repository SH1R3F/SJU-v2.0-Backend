<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\PageResource;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = Page::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total' => Page::filter($request)->count(),
          'pages' => PageResource::collection($pages)
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
        $validator = Validator::make($request->all(), [
          'title_ar'   => 'required',
          'title_en'   => 'required',
          'slug'       => 'required|unique:pages,slug',
          'content_ar' => 'required',
          'content_en' => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $page = Page::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create')
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
      return new PageResource($page);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
          'title_ar'   => 'required',
          'title_en'   => 'required',
          'slug'       => 'required|unique:pages,slug,' . $page->id,
          'content_ar' => 'required',
          'content_ar' => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $page->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json([
          'message' => __('messages.successful_delete')
        ]);

    }
}
