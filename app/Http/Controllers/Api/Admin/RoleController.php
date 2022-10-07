<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:manage-roles');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $roles = Role::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total' => Role::get()->count(),
          'roles' => $roles
        ]);
    }

    /**
     * Display a listing of the permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function get_permissions(Request $request, Role $role)
    {
        $all_permissions  = Permission::all()->pluck('name');
        $role_permissions = $role->permissions()->pluck('name');

        $permissions  = [];
        $abilities = [];

        foreach ($all_permissions as $p) {
          $permission = explode('-', $p);
          array_push($permissions, [
            'action'  => $permission[0],
            'subject' => $permission[1],
            'granted' => in_array($p, $role_permissions->toArray())
          ]);
          
          $abilities[$p] = in_array($p, $role_permissions->toArray());
        }

        return response()->json([
          'permissions'  => $permissions,
          'abilities'    => $abilities
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
          'display_name' => 'required|min:3',
          'description'  => 'required|min:3'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }


        $name = Str::slug($request->display_name);
        $c    = Role::where('name', $name)->count();
        if ($c) {
          $name = Str::slug("$name $c");
        }
        $request->merge(['name' => $name]);


        // Update
        $role = Role::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'role'   => $role
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
      return $role;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'display_name' => 'required|min:3',
          'description'  => 'required|min:3'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        } 
        
        $name = Str::slug($request->display_name);
        $c    = Role::where('name', $name)->count();
        if ($c) {
          $name = Str::slug("$name $c");
        }
        $request->merge(['name' => $name]);

        // Update
        $role->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);

    }

    /**
     * Update the permissions in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update_permissions(Request $request, Role $role)
    {

        $arr = array_filter( $request->permissions, function($permission) {
            return $permission;
          });

        $permissions = Permission::whereIn('name', array_keys($arr))->get();

        // Update
        $role->permissions()->sync($permissions);
        
        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // Delete database record
        $role->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
