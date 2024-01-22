<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
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
     * @dataProvider invalidUsersData
     */
    public function test_it_throws_validation_when_register($requestData): void
    {
        User::factory()->create(['email' => 'test@gmail.com', 'phone_number' => 12345]);
        $response = $this->postJson(self::API_URL.'auth/register', $requestData);

        $response->assertStatus(422);
        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @see test_it_throws_validation_when_register
     */
    public static function invalidUsersData(): array
    {
        return [
            'only-email' => [
                ['first_name' => 'aaa'],
            ],
            'empty-firstname' => [
                ['email' => 'test@gmail.com'],
            ],
            'used-email' => [
                ['email' => 'test@gmail.com'],
            ],
            'used-phone_number' => [
                ['email' => 12345],
            ],
        ];
    }

    /**
     * @test
     */
    public function test_it_registers_user(): void
    {
        $password = fake()->password;
        $registerData = [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->safeEmail,
            'phone_number' => fake()->randomNumber(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(self::API_URL.'auth/register', $registerData);

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
