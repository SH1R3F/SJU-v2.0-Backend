<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roles(Request $request)
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
        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

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
        // Hash password
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        // Update Avatar
        if ($request->avatar) {
            if (str_starts_with($request->avatar, 'data:image')) {
                $base64Image  = explode(";base64,", $request->avatar);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType    = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $imageName    = uniqid() . '.' . $imageType;
                Storage::disk('public')->put("admins/{$admin->id}/images/{$imageName}", $image_base64);
                $request->merge(['avatar' => $imageName]);

                // Delete previous image from disk
                Storage::disk('public')->delete("admins/{$admin->id}/images/{$admin->avatar}");
            } else {
                $request->merge(['avatar' => $admin->avatar]);
            }
        } else if ($admin->image) { // If admin had avatar then deleted.
            // Delete file from disk
            Storage::disk('public')->delete("admins/{$admin->id}/images/{$admin->image}");
            // Null db value
            $request->merge(['image' => null]);
        }


        // Update
        $admin->update($request->all());

        // Attach role
        $admin->roles()->sync([$request->role_id]);

        return response()->json([
            'message' => __('messages.successful_update')
        ], 200);
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
