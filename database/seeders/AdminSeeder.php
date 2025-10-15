<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => env('USER_NAME'),
                'email' => env('USER_EMAIL'),
                'email_verified_at' => now(),
                'password' => bcrypt(env('USER_PASSWORD')),
                'role' => ['admin', 'guest', 'estimator', 'manager']
            ],
            [
                'name' => 'Jim Halpert',
                'email' => 'jimhalpert@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt(env('USER_PASSWORD')),
                'role' => 'guest'
            ],
            [
                'name' => 'Pam Beesley',
                'email' => 'pambeesley@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt(env('USER_PASSWORD')),
                'role' => 'estimator'
            ],
            [
                'name' => 'Michael Scott',
                'email' => 'michaelscott@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt(env('USER_PASSWORD')),
                'role' => 'manager'
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => $user['password'],
            ])->assignRole($user['role']);
        }
    }
}
