<?php

namespace Database\Factories;

use App\Models\Customer;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
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
            'customer_id' => Customer::query()->inRandomOrder()->first()->id,
            'date_created' => $date_created,
            'due_date' => $due_date,
            'reference' => 'REF-'.rand(10, 500),
            'terms_and_conditions' => 'Payment is due within 30 days of invoice date. Late payments may incur additional charges. Please retain this invoice for your records.',
            'discount' => $this->faker->numberBetween(10, 100),
            'paid' => $this->faker->numberBetween(0, 1)
        ];
    }
}
