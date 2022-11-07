<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Member;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\VerifyUsersEmailRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \VerifyUsersEmailRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(VerifyUsersEmailRequest $request)
    {

        $user = $request->user();
        // if ($user instanceof Volunteer) {
        //   $user->status = 1;
        //   $user->save();
        // }
      
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'?verified=1'
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(
            config('app.frontend_url').'?verified=1'
        );
    }


    public function resend(Request $request)
    {
      if (!$request->type || !$request->email ) return false;
      
      switch($request->type) {
        case 'volunteer':
          $user = Volunteer::where('email', $request->email)->first();
          break;
        case 'member':
          $user = Member::where('email', $request->email)->first();
          break;
        case 'subscriber':
          $user = Subscriber::where('email', $request->email)->first();
          break;
      }

      $user->sendEmailVerificationNotification();
      return response()->json([
        'message' => __('messages.verification_sent'),
      ]);
    }
}
