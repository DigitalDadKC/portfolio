<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientInvoice>
 */
class ClientInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeThisDecade();
        $date_created = $date->format('Y-m-d');
        $due_date = $date->modify('+30 days')->format('Y-m-d');

        return [
            'number' => $this->faker->unique()->numberBetween(10, 1000),
            'date_created' => $date_created,
            'due_date' => $due_date,
            'terms_and_conditions' => 'Payment is due within 30 days of invoice date. Late payments may incur additional charges. Please retain this invoice for your records.',
            'paid' => $this->faker->randomElement(['paid', 'unpaid']),
            'session_id' => $this->faker->randomNumber(9, true),
            'client_id' => Client::query()->inRandomOrder()->first()->id,
        ];
    }
}
