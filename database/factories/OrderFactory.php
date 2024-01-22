<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'event_id' => Event::inRandomOrder()->first()->id ?? Event::factory()->create()->id,
            'status' => OrderStatus::PENDING->value,
            'total_price' => fake()->randomFloat(2, 0, 1000),
            'session_id' => \Str::random(20),
        ];
    }
}
