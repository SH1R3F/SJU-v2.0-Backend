<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private function loggedInUser()
    {
      $user = null;
      if (Auth::guard('api-volunteers')->check()) {
        $user = Auth::guard('api-volunteers')->user();
      } elseif (Auth::guard('api-subscribers')->check()) {
        $user = Auth::guard('api-volunteers')->user();
      } elseif (Auth::guard('api-members')->check()) {
        $user = Auth::guard('api-volunteers')->user();
      }
      return $user;
    }

    public function logout()
    {
      $user = $this->loggedInUser();
      // Destroy User Tokens
      $user->tokens()->delete();

      // Return Success Message
      return response()->json([
        'message' => __('messages.successful_logout')
      ], 200);
    }

}
