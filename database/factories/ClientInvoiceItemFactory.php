<?php

namespace Database\Factories;

use App\Models\ClientInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientInvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(100,5000),
            'quantity' => $this->faker->numberBetween(1, 5),
            'client_invoice_id' => ClientInvoice::query()->inRandomOrder()->first()->id,
        ];
    }
}
