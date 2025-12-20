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
use App\Models\MaterialUnitSize;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Line::truncate();
        Scope::truncate();
        Proposal::truncate();
        Job::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Job::factory(50)
            ->create()
            ->each(fn($job) => $job->proposals()->saveMany(Proposal::factory(mt_rand(1, 3))->create()
                ->each(fn($proposal) => $proposal->scopes()->saveMany(Scope::factory(mt_rand(1, 3))->create()
                    ->each(fn($scope) => $scope->lines()->saveMany(Line::factory(mt_rand(1, 3))
                    ->state(new Sequence(
                        fn(Sequence $sequence) => [
                            'order' => $sequence->index
                        ]
                    ))
                    ->create()))
                ))
            ));

        // Invoice::factory(100)->create();
        // InvoiceItem::factory(250)->create();
        // Product::factory(3)->create();
    }
}
