<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Skillio',
                'username' => 'admin',
                'password' => 'admin123',
                'role' => 'admin',
                'status' => 1,
            ],
            [
                'name' => 'Kasir Skillio',
                'username' => 'kasir',
                'password' => 'kasir123',
                'role' => 'kasir',
                'status' => 1,
            ],
            [
                'name' => 'Owner Skillio',
                'username' => 'owner',
                'password' => 'owner123',
                'role' => 'owner',
                'status' => 1,
            ],
        ];

        foreach ($users as $user) {
            // This will update if exists, or create if not
            User::updateOrCreate(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                    'status' => $user['status'],
                ]
            );
        }
        
        $this->command->info('Users seeded successfully!');
    }
}