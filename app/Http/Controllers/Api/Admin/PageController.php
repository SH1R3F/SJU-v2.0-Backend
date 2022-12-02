<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\PageResource;

class PageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-page', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-page', ['only' => 'store']);
        $this->middleware('permission:update-page', ['only' => 'update']);
        $this->middleware('permission:delete-page', ['only' => 'destroy']);
    }

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
     * @param  PageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        Page::create($request->all());
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
     * @param  PageRequest  $request
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, Page $page)
    {
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
