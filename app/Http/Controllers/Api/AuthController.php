<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponse;

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
            'user'  => $user,
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
}