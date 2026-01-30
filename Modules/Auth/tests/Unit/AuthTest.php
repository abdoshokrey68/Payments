<?php

namespace Modules\Auth\Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Services\AuthService;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService;
    }

    public function test_register_new_user(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('12345678'),
        ];

        $user = $this->service->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    }

    public function test_login_user_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $result = $this->service->login([
            'email' => 'user@test.com',
            'password' => '12345678',
        ]);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user->id, $result->id);
    }

    public function test_login_user_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $result = $this->service->login([
            'email' => 'user@test.com',
            'password' => '1111111111',
        ]);

        $this->assertNull($result);
    }

    public function test_revoke_user_tokens(): void
    {
        $user = User::factory()->create();
        $user->createToken('test')->plainTextToken;

        $this->assertGreaterThan(0, $user->tokens()->count());

        $this->service->revokeTokens($user);

        $this->assertSame(0, $user->tokens()->count());
    }
}
