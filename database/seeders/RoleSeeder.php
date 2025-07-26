<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'guest']);
        Role::create(['name' => 'estimator']);

        $role->givePermissionTo([
            'view jobs',
            'manage jobs',
            'view company',
            'manage company',
        ]);
    }
}
