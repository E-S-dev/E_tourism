<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programme>
 */
class ProgrammeFactory extends Factory
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
            'type'=> $this->faker->name(),
            'name'=> $this->faker->City(),
            'description' => $this->faker->Paragraph(),
            'startDate' => $this->faker->dateTimeBetween('2020-01-01', '2024-12-31')->format('Y-m-d'),
            'endDate' => $this->faker->dateTimeBetween('2020-01-01', '2024-12-31')->format('Y-m-d'),
        ];
    }
}
