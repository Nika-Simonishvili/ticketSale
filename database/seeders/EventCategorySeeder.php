<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Music'],
            ['name' => 'Sport'],
            ['name' => 'Tech'],
        ];

        foreach ($data as $eventCategory) {
            EventCategory::create($eventCategory);
        }
    }
}
