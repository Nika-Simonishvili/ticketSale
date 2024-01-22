<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'status' => BookingStatus::PENDING->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory()->create()->id,
        ];
    }
}
