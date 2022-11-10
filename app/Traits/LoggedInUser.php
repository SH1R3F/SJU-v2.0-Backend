<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\MemberResource;
use App\Http\Resources\Admin\VolunteerResource;
use App\Http\Resources\Admin\SubscriberResource;

trait loggedInUser {

    public function loggedInUser()
    {
      $user = null;
      $type = null;
      if (Auth::guard('api-volunteers')->check()) {
        $user = new VolunteerResource(Auth::guard('api-volunteers')->user());
        $type = 'volunteer';
      } elseif (Auth::guard('api-subscribers')->check()) {
        $user = new SubscriberResource(Auth::guard('api-subscribers')->user());
        $type = 'subscriber';
      } elseif (Auth::guard('api-members')->check()) {
        $user = new MemberResource(Auth::guard('api-members')->user());
        $type = 'member';
      }
      return [
        'user' => $user,
        'type' => $type
      ];
    }
}