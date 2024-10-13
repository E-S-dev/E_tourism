<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tour;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tourist>
 */
class TouristFactory extends Factory
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
            'fName' => $this->faker->FirstName($gender = 'male'|'female'),
            'lName' => $this->faker->LastName(),
            'phoneNumber' => $this->faker->e164PhoneNumber(),
            'description' => $this->faker->Paragraph(),
            'tour_id' => Tour::factory()
        ];
    }
}
