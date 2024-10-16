<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guide;
use App\Models\Driver;
use App\Models\Programme;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //generate random data with faker
            'guide_id' => Guide::factory(),
            'driver_id' => Driver::factory(),
            'programme_id' => Programme::factory(),
            'date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(1, 20, 30),
            'description' => $this->faker->Paragraph(),
        ];
    }
}
