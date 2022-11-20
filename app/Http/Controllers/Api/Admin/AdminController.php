<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\AdminResource;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-moderator', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-moderator', [ 'only' => 'store']);
        $this->middleware('permission:update-moderator', [ 'only' => 'update']);
        $this->middleware('permission:delete-moderator', [ 'only' => 'destroy']);
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
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'email'     => 'required|email|unique:admins,email',
          'password'  => 'required|min:6',
          'username'  => 'required|min:3|unique:admins,username',
          'mobile'    => 'required',
          'role'      => 'required|exists:roles,id',
          'branch_id' => 'nullable'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

        // Update
        $admin = Admin::create($request->all());

        // Attach role
        $admin->attachRole(Role::find($request->role));

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
    public function update(Request $request, Admin $admin)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'adminEmail' => 'required|email|unique:admins,email,' . $admin->id,
          'password'   => 'nullable|min:6',
          'username'   => 'required|min:3|unique:admins,username,' . $admin->id,
          'mobile'     => 'required',
          'role_id'    => 'required|exists:roles,id',
          'branch_id'  => 'nullable'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        } 

        // Admin email
        if ($request->adminEmail) {
          $request->merge(['email' => $request->adminEmail]);
        }

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
            $imageName    = uniqid() . '.'.$imageType;
            Storage::disk('public')->put("admins/{$admin->id}/images/{$imageName}", $image_base64);
            $request->merge(['avatar' => $imageName]);

            // Delete previous image from disk
            Storage::disk('public')->delete("admins/{$admin->id}/images/{$admin->avatar}");
            
          } else {
            $request->merge(['avatar' => $admin->avatar]);
          }

        } else if($admin->image) { // If admin had avatar then deleted.
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
