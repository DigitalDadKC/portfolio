<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialUnitSize>
 */
class MaterialUnitSizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unit_size' => $this->faker->randomElement(['Each', 'Unit', 'Kit', 'Pallet', 'Case', 'Bundle'])
        ];
    }
}
