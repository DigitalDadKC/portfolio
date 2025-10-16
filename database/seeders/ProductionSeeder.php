<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Line;
use App\Models\Scope;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(ProductionSqlSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        MaterialCategory::factory(10)->sequence(fn(Sequence $sequence) => ['name' => 'category #' . $sequence->index + 1])->create();
        Material::factory(50)->create();

        Customer::factory(50)->create();
        Job::factory(250)->create();
        Proposal::factory(400)->create();
        Scope::factory(600)->create();
        Line::factory(950)->create();
        Client::factory(200)->create();
        Invoice::factory(100)->create();
        InvoiceItem::factory(250)->create();
    }
}
