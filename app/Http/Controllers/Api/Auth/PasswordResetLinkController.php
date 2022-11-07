<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\UserTypeClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{

    use UserTypeClass;

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $type
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $type)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);


        $user = $this->userTypeClass($type)->where('email', $request->email)->first();
        if (is_null($user)) {
            $status = PasswordBroker::INVALID_USER;
        }

        // // Throttling
        // if (app('auth.password.broker')->recentlyCreatedToken($user)) {
        //     $status = PasswordBroker::RESET_THROTTLED;
        // }

        if (isset($status)) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
        

        // Delete old token if exists
        DB::table('password_resets')->where([
          'email'     => $request->email,
          'user_type' => get_class($user)
        ])->delete();

        // Create our token
        $token = Str::random(60);
        DB::table('password_resets')->insert([
          'email'      => $request->email,
          'user_type'  => get_class($user),
          'token'      => $token,
          'created_at' => Carbon::now()
        ]);


        $user->sendPasswordResetNotification($token);
        $status = PasswordBroker::RESET_LINK_SENT;

        return response()->json(['message' => __($status)]);
    }
}
