<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public const API_URL = 'api/';

    protected function login(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function getDecodedResponse(TestResponse $response): array
    {
        return json_decode($response->content(), true);
    }
}
