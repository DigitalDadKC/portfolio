<?php

namespace Database\Factories;

use App\Models\MaterialCategory;
use App\Models\MaterialUnitSize;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unit_size_ids = MaterialUnitSize::pluck('id')->toArray();
        $category_ids = MaterialCategory::pluck('id')->toArray();
        return [
            'sku' => strtoupper(fake()->randomLetter() . fake()->unique()->numberBetween(0, 10000)),
            'name' => "Product " . fake()->words(2, true),
            'price' => fake()->numberBetween(100, 10000),
            'discountable' => fake()->boolean(),
            'material_unit_size_id' => fake()->randomElement($unit_size_ids),
            'material_category_id' => fake()->randomElement($category_ids),
        ];
    }
}
