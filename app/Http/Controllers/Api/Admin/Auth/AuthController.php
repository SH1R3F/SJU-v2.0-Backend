<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use Validator;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\AdminResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      // Validate email and password
      $validation = Validator::make($request->all(), [
        'email' => 'required|string',
        'password' => 'required|string'
      ]);
      if ($validation->fails()) {
        $errors = $validation->errors();
        return response($errors->toJson(), 422);
      }

      // Check login
      $admin = Admin::where('email', $request->email)->with('roles')->first();
      if (!$admin || !Hash::check($request->password, $admin->password)) {
        return response(['message' => __('messages.invalid_credentials')], 422);
      }

      // Revoking previous tokens
      // $admin->tokens()->delete();

      return response([
        'userData'  => new AdminResource($admin),
        'accessToken' => $admin->createToken('accessToken', ['server-update'])->plainTextToken
      ]);

    }
}
