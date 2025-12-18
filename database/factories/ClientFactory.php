<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => $this->faker->company,
            'email' => $this->faker->email,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state_id' => State::query()->inRandomOrder()->first()->id,
            'zip' => $this->faker->randomNumber(5, true),
            'url' => $this->faker->url(),
        ];
    }
}
