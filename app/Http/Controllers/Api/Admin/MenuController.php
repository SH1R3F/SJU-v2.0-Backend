<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\MenuResource;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-menu', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-menu', ['only' => 'store']);
        $this->middleware('permission:update-menu', ['only' => ['update', 'reorder']]);
        $this->middleware('permission:delete-menu', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::orderBy('order', 'ASC')->get();
        return response()->json([
            'total' => Menu::count(),
            'menus' => MenuResource::collection($menus)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        Menu::create($request->all());

        return response()->json([
            'message' => __('messages.successful_create')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  MenuRequest  $request
     * @param  Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->all());

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
        foreach ($request->menus as $k => $menu) {
            Menu::where('id', $menu['id'])->update(['order' => $k + 1]);
        }

        return response()->json([
            'message' => __('messages.successful_update')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ]);
    }
}
