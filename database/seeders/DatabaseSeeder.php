<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Line;
use App\Models\Scope;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
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
        MaterialCategory::factory(10)->sequence(fn(Sequence $sequence) => ['name' => 'category #' . $sequence->index + 1])->create();
        Material::factory(50)->create();

        Customer::factory(50)->create();
        Job::factory(50)->create();
        Proposal::factory(100)->create();
        Scope::factory(250)->create();
        Line::factory(400)->create();
        Client::factory(200)->create();
        Invoice::factory(100)->create();
        InvoiceItem::factory(250)->create();
        Product::factory(3)->create();
    }
}
