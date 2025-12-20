<?php

namespace Database\Factories;

use App\Models\Line;
use App\Models\Scope;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Line>
 */
class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'description' => $this->faker->word(),
            'unit_of_measurement_id' => UnitOfMeasurement::query()->inRandomOrder()->first()->id,
            'days' => $this->faker->randomDigit(),
            'price' => $this->faker->randomNumber(4, false),
            'quantity' => $this->faker->randomNumber(4, false),
            'order' => 0,
            'scope_id' => Scope::query()->inRandomOrder()->first()->id
        ];
    }
}
