<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Line;
use App\Models\Scope;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Material;
use App\Models\Outreach;
use App\Models\Proposal;
use App\Models\InvoiceItem;
use App\Models\ClientInvoice;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use App\Models\MaterialUnitSize;
use App\Models\ClientInvoiceItem;
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
        MaterialUnitSize::factory(6)->create();
        Material::factory(50)->create();

        Customer::factory(50)->create();
        Job::factory(50)->create();
        Proposal::factory(100)->create();
        Scope::factory(150)->create();
        Line::factory(200)->create();

        Client::factory(20)->create();
        ClientInvoice::factory(50)->create();
        ClientInvoiceItem::factory(100)->create();
        Outreach::factory(50)->create();

        Product::factory(25)->create();
        Invoice::factory(50)->create();
        InvoiceItem::factory(100)->create();
        Service::factory(15)->create();
        Employee::factory(35)->create();
    }
}
