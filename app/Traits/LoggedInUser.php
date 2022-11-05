<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\VolunteerResource;

trait loggedInUser {

    public function loggedInUser()
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
}