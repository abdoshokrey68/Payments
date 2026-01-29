<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Transformers\AuthResource;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $service
    ) {}

    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->only('email', 'password'));

        if (! $user) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user' => new AuthResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->create($request->validated());

        return ApiResponse::success(new AuthResource($user), 'Registration successful');
    }

    public function logout(Request $request)
    {
        $this->service->revokeTokens($request->user());

        return ApiResponse::success(null, 'Logout successful');
    }
}
