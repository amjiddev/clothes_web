<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
        public function create()
        {
            return view('pages/auth.forgot-password');
        }

        public function store(Request $request)
        {
            $request->validate(['email' => ['required', 'email']]);

            $email = strtolower(trim($request->input('email')));
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            if (!$user) {
                return response()->json([
                    'message' => 'We could not find an account with that email address.',
                ], 422);
            }

            $code = (string) random_int(1000, 9999);

            DB::table('password_reset_otps')->updateOrInsert(
                ['email' => $email],
                [
                    'code_hash' => Hash::make($code),
                    'expires_at' => now()->addMinutes(10),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            try {
                Mail::raw("Your password reset verification code is: {$code}\n\nThis code expires in 10 minutes.", function ($message) use ($email) {
                    $message->to($email)->subject('Password Reset Verification Code');
                });
            } catch (\Throwable $exception) {
                return response()->json([
                    'message' => 'We could not send the verification code. Please check the mail configuration.',
                ], 500);
            }

            return response()->json([
                'email' => $email,
                'message' => 'Verification code sent successfully.',
            ]);
        }

        public function showVerify(Request $request)
        {
            return view('pages/auth.verify-password-code', ['email' => $request->query('email')]);
        }

        public function verify(Request $request)
        {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'code' => ['required', 'digits:4'],
            ]);

            $email = strtolower(trim($validated['email']));
            $otp = DB::table('password_reset_otps')->where('email', $email)->first();

            if (!$otp || now()->greaterThan($otp->expires_at) || !Hash::check($validated['code'], $otp->code_hash)) {
                return response()->json([
                    'message' => 'The verification code is invalid or has expired.',
                ], 422);
            }

            $user = User::whereRaw('LOWER(email) = ?', [$email])->firstOrFail();
            $token = Password::createToken($user);
            DB::table('password_reset_otps')->where('id', $otp->id)->delete();

            return response()->json([
                'redirect' => route('password.reset', [
                    'token' => $token,
                    'email' => $user->email,
                ]),
            ]);
        }
}
