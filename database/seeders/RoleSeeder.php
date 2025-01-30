<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = collect(['admin', 'Project Manager', 'Team Manager']);

        try {
            DB::transaction(function () use ($roles) {
                foreach ($roles as $roleName) {
                    // if (!Role::where('name', $roleName)->exists()) {
                    //     Role::create(['name' => $roleName]);
                    // } else {
                    //     Log::info("Role '{$roleName}' already exists.");
                    // }

                    $role = Role::firstOrCreate(['name' => $roleName]);
                    !$role->wasRecentlyCreated? Log::info("Role '{$roleName}' already exists."):'';

                }
            });
        } catch (\Exception $e) {
            Log::error("Failed to create roles: " . $e->getMessage());
        }
    }
}
