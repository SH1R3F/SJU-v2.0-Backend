<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Volunteer;
use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\VolunteerResource;

class AuthController extends Controller
{

    use LoggedInUser;

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
