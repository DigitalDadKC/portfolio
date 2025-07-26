<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view jobs']);
        Permission::create(['name' => 'manage jobs']);
        Permission::create(['name' => 'view company']);
        Permission::create(['name' => 'manage company']);
    }
}
