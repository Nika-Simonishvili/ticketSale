<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $ticketsQuantity = rand(10, 20);

        return [
            'title' => fake()->word,
            'start_date_time' => now(),
            'address' => fake()->address,
            'tickets_quantity' => $ticketsQuantity,
            'available_tickets_quantity' => $ticketsQuantity,
            'user_id' => User::factory()->create()->first()->id,
            'event_category_id' => EventCategory::factory()->create()->first()->id,
        ];
    }
}
