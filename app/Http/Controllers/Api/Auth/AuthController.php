<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\VolunteerResource;

class AuthController extends Controller
{

    private function loggedInUser()
    {
      $user = null;
      $type = null;
      if (Auth::guard('api-volunteers')->check()) {
        $user = new VolunteerResource(Auth::guard('api-volunteers')->user());
        $type = 'volunteer';
      } elseif (Auth::guard('api-subscribers')->check()) {
        $user = Auth::guard('api-subscribers')->user();
        $type = 'subscriber';
      } elseif (Auth::guard('api-members')->check()) {
        $user = Auth::guard('api-members')->user();
        $type = 'member';
      }
      return [
        'user' => $user,
        'type' => $type
      ];
    }

    public function user()
    {

      $user = $this->loggedInUser();

      return response()->json($user);
    }

    public function logout()
    {
      $user = $this->loggedInUser()['user'];
      // Destroy User Tokens
      $user->tokens()->delete();

      // Return Success Message
      return response()->json([
        'message' => __('messages.successful_logout')
      ], 200);
    }

}
