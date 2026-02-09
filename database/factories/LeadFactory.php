<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    public function definition(): array
    {
        $topics = ['insurance', 'car purchase', 'loan inquiry', 'test drive', 'service booking'];

        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'source' => $this->faker->randomElement(['web', 'facebook', 'walkin']),
            'description' => $this->faker->sentence() . ' ' . $this->faker->randomElement($topics),
        ];
    }
}
