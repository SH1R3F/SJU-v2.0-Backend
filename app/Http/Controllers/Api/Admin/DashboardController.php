<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TechnicalSupportTicket;

class DashboardController extends Controller
{
    public function index()
    {

      $members     = Member::count();
      $subscribers = Subscriber::count();
      $volunteers  = Volunteer::count();
      $tickets     = TechnicalSupportTicket::count();
      $open        = TechnicalSupportTicket::where('status', 1)->count();
      $closed      = $tickets - $open;
      $courses     = Course::count();
      $attendees   = DB::table('course_user')->count();
      $passed      = DB::table('course_user')->where('attendance', 1)->count();

      return response()->json([
          'members' => $members,
          'subscribers' => $subscribers,
          'volunteers' => $volunteers,
          'tickets' => [
            'total' => $tickets, 
            'open' => $open,
            'closed' => $closed,
          ],
          'courses' => [
            'total' => $courses, 
            'attendees' => $attendees,
            'passed' => $passed,
          ]
        ]);
    }
}
