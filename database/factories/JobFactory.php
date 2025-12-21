<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->randomNumber(5, true),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state_id' => State::query()->inRandomOrder()->first()->id,
            'zip' => $this->faker->randomNumber(5, true),
            'start_date' => $this->faker->dateTimeThisYear(),
            'prevailing_wage' => $this->faker->boolean(),
            'notes' => $this->faker->paragraph(2),
            'customer_id' => Customer::query()->inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeBetween('-2 years')
        ];
    }
}
