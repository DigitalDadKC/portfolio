<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ScopeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'area' => $this->faker->randomNumber(5),
            'proposal_id' => Proposal::query()->inRandomOrder()->first()->id
        ];
    }
}
