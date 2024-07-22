<?php

namespace Database\Factories;

use App\Enums\TicketType;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::inRandomOrder()->first()->id ?? Event::factory()->create()->id,
            'price' => fake()->randomFloat(2, 0, 1000),
            'row_number' => fake()->numberBetween(1, 50),
            'seat_number' => fake()->numberBetween(1, 50),
            'type' => fake()->randomElement([TicketType::DEFAULT, TicketType::STUDENT, TicketType::VIP]),
            'available' => fake()->boolean,
        ];
    }
}
