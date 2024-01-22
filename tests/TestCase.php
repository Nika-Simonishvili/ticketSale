<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public const API_URL = 'api/';

    public function login(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }
}
