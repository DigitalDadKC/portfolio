<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use App\Models\ProposalType;
use App\Enums\ProposalTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'contingency' => $this->faker->randomNumber(5),
            'job_id' => Job::query()->inRandomOrder()->first()->id,
            'type' => fake()->randomElement(ProposalTypeEnum::cases()),
            'exclusions' => $this->faker->paragraphs(3, true),
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
