<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SqlSeeder extends Seeder
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
            $path = 'database/seeders/local/' . Str::of(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model)))->plural() . '.sql';
            DB::unprepared(file_get_contents($path));
            $this->command->info($model . ' Model Seeded!');
        }
        DB::unprepared(file_get_contents('database/seeders/local/' . Str::of('project_skill') . '.sql'));
    }
}
