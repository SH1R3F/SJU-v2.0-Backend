<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Resources\Admin\AdminResource;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-moderator', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-moderator', ['only' => 'store']);
        $this->middleware('permission:update-moderator', ['only' => 'update']);
        $this->middleware('permission:delete-moderator', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admins = Admin::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
            'total'      => Admin::get()->count(),
            'moderators' => AdminResource::collection($admins)
        ]);
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {

        $roles = Role::all();
        return response()->json([
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        // Create
        $admin = Admin::create($request->all());
        // Attach role
        $admin->attachRole(Role::find($request->role));

        // Response
        return response()->json([
            'message' => __('messages.successful_create'),
            'admin'   => new AdminResource($admin)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        // If deleted his avatar
        if (!$request->avatar && $admin->avatar) {
            // Delete file from disk
            Storage::disk('public')->delete("admins/{$admin->id}/images/{$admin->avatar}");
        }

        // Update Base64 Avatar is uploaded
        if ($request->avatar && str_starts_with($request->avatar, 'data:image')) {
            $name = upload_base64_image($request->avatar, "admins/{$admin->id}/images");
            $request->merge(['avatar' => $name]);
            // Delete previous image from disk
            Storage::disk('public')->delete("admins/{$admin->id}/images/{$admin->avatar}");
        }

        // Update
        $admin->update($request->all());
        // Update role
        $admin->roles()->sync([$request->role_id]);

        return response()->json(['message' => __('messages.successful_update')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        // Delete his files on desk
        Storage::disk('public')->deleteDirectory("admins/{$admin->id}");
        // Delete database record
        $admin->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ], 200);
    }
}
