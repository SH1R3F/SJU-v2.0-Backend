<?php 

namespace App\Traits;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\VolunteerResource;

trait UserTypeClass {

    public function userTypeClass($type)
    {
      switch($type) {
        case 'volunteer':
          return new Volunteer;
          break;
        case 'subscriber':
          return new Subscriber;
          break;
        case 'member':
          return new Member;
          break;
      }
    }
}