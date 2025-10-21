<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Line;
use App\Models\Scope;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            'MaterialUnitSize',
            'Skills',
            'Projects',
            'Features',
            'UnitOfMeasurements',
            'States',
            'Companies',
            'CsiDivisions',
            'CsiSections',
            'CsiSubsections'
        ];
        foreach ($models as $model) {
            $path = 'database/seeders/production/' . Str::of(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model)))->plural() . '.sql';
            DB::unprepared(file_get_contents($path));
            $this->command->info($model . ' Model Seeded!');
        }
        DB::unprepared(file_get_contents('database/seeders/production/' . Str::of('project_skill') . '.sql'));

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
        Invoice::factory(100)->create();
        InvoiceItem::factory(250)->create();
        Product::factory(3)->create();
    }
}
