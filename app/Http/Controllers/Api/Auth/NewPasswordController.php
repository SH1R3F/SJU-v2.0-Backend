<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\UserTypeClass;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{

    use UserTypeClass;

    /**
     * Handle an incoming new password request.
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
            'token'        => ['required'],
            'email'        => ['required', 'email'],
            'new_password' => ['required', Rules\Password::defaults()],
        ]);

        $user = $this->userTypeClass($type)->where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'token' => [__(PasswordBroker::INVALID_USER)],
            ]);
        }


        // Manually validate token
        $row = DB::table('password_resets')->where([
          'token'     => $request->token,
          'email'     => $request->email,
          'user_type' => 'App\Models\\' . ucfirst($type)
        ]);
        if (!$row->first()) {
            throw ValidationException::withMessages([
                'token' => [__('messages.invalid_token')],
            ]);
        }

        // Update the password of the user
        $user->forceFill([
            'password' => Hash::make($request->new_password)
        ])->save();

        // Delete token from database!
        $row->delete();

        event(new PasswordReset($user));
        
        return response()->json([
          'message' => __('messages.successful_update')
        ]);
    }
}
