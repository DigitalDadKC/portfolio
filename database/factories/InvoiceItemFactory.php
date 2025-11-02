<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::query()->withTrashed()->inRandomOrder()->first()->id,
            'material_id' => Material::query()->inRandomOrder()->first()->id,
            'unit_price' => $this->faker->numberBetween(100,5000),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
