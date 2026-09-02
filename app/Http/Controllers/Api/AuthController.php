<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Member;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {

            return $this->error(
                'Invalid credentials.',
                401
            );
        }

        $user = Auth::user();

        $token = $user->createToken('library-api')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(
            null,
            'Logged out successfully.'
        );
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {

            $data = $request->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'member',
            ]);

            $member = Member::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);

            DB::commit();

            return $this->success(
                [
                    'user' => $user,
                    'member' => $member,
                ],
                'Member registered successfully.',
                201
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('Member registration failed.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
{
    $status = Password::sendResetLink(
        $request->validated()
    );

    if ($status !== Password::RESET_LINK_SENT) {
        return $this->error(
            __($status),
            422
        );
    }

    return $this->success(
        null,
        'Password reset link sent successfully.'
    );
}

public function resetPassword(ResetPasswordRequest $request)
{
    $status = Password::reset(
        $request->validated(),
        function ($user, $password) {

            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();

            $user->tokens()->delete();
        }
    );

    if ($status !== Password::PASSWORD_RESET) {
        return $this->error(
            __($status),
            422
        );
    }

    return $this->success(
        null,
        'Password reset successfully.'
    );
}
}
