<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => fake()->name(),
                'email' => 'admin@gmail.com',
                'password' => 'password', // Use a secure password
                'role' => 'admin',
            ],
            [
                'name' => fake()->name(),
                'email' => 'projectMgt@gmail.com',
                'password' => 'password',
                'role' => 'Project Manager',
            ],
            [
                'name' => fake()->name(),
                'email' => 'team@gmail.com',
                'password' => 'password',
                'role' => 'Team Manager',
            ],
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => 'password',
                'role' => 'Team Manager',
            ],
        ];

        try {
            DB::transaction(function () use ($users) {
                foreach ($users as $userData) {
                    $user = User::firstOrCreate(
                        ['email' => $userData['email']], // Check for existing user by email
                        [
                            'name' => $userData['name'],
                            'password' => Hash::make($userData['password']), // Hash the password
                        ]
                    );
                    !$user->wasRecentlyCreated ? logger()->info("User '{$user->email}' already exists."):'';

                    if ($userData['role']) {
                        $user->assignRole($userData['role']);
                    }
                }
            });
        } catch (\Exception $e) {
            logger()->error("Failed to create User: " . $e->getMessage());
        }

    }
}
