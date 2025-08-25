<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Line;
use App\Models\Scope;
use App\Models\Client;
use App\Models\Counter;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use App\Models\MaterialEffectiveDate;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SqlSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);

        // Initial Contract guaranteed
        MaterialEffectiveDate::factory()->create([
            'contract_id' => NULL,
            'effective_date' => fake()->randomElement(['2014-01-01', '2017-01-01', '2020-01-01', '2023-01-01'])
        ]);

        // Creates 15 contracts and each contract has between 1 and 3 related dates.
        Contract::factory(15)->create()->each(function ($contract) {
            MaterialEffectiveDate::factory(rand(1, 3))->create(['contract_id' => $contract->id]);
        });

        MaterialCategory::factory(10)->sequence(fn(Sequence $sequence) => ['Name' => 'Category #' . $sequence->index + 1])->create();
        Material::factory(50)->create();

        Customer::factory(50)->create();
        Job::factory(250)->create();
        Proposal::factory(400)->create();
        Scope::factory(600)->create();
        Line::factory(950)->create();
        Counter::factory(1)->create();
        Product::factory(5)->create();
        Client::factory(5)->create();
        Invoice::factory(5)->create();
        InvoiceItem::factory(5)->create();
    }
}
