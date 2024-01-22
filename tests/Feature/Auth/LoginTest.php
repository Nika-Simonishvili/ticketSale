<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_throws_unauthorized(): void
    {
        $response = $this->deleteJson(self::API_URL.'auth/logout');

        $response->assertUnauthorized();
    }

    /**
     * @test
     *
     * @dataProvider wrongLoginData
     */
    public function test_it_throws_validation_errors_on_login($requestData): void
    {
        User::factory()->create(['email' => 'wrongEmail@gmail.com', 'password' => 'otherPassword']);
        User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $response = $this->postJson(self::API_URL.'auth/login', $requestData);

        $response->assertStatus(422);
    }

    /**
     * @see test_it_throws_validation_errors_on_login
     */
    public static function wrongLoginData(): array
    {
        return [
            'wrong-email' => [
                [
                    'email' => 'wrongEmail@gmail.com',
                    'password' => 'password',
                ],
            ],
            'wrong-password' => [
                [
                    'email' => 'test@gmail.com',
                    'password' => 'wrongPassword',
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function test_it_logs_in_user(): void
    {
        $userData = ['email' => 'test@gmail.com', 'password' => 'password'];
        User::factory()->create($userData);

        $response = $this->postJson(self::API_URL.'auth/login', $userData);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'user' => [
                'first_name',
                'last_name',
                'email',
                'phone_number',
            ],
        ]);
        $this->assertDatabaseCount('users', 1);
    }
}
