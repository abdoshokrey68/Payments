<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function login(array $credentials): ?User
    {
        if (! Auth::attempt($credentials)) {
            return null;
        }

        return Auth::user();
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
