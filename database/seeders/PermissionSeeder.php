<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsData = collect([
            'Create Role', 'View Role', 'Edit Role', 'Delete Role',
            'Create Permission', 'View Permission', 'Edit Permission', 'Delete Permission',
            'Create Menu', 'View Menu', 'Edit Menu', 'Delete Menu',
            'Create User', 'View User', 'Edit User', 'Delete User',
            'Create Project', 'View Project', 'Edit Project', 'Delete Project',
        ]);

        try {
            DB::transaction(function () use ($permissionsData) {
                foreach ($permissionsData as $permissionName) {
                    // if (!Permission::where('name', $permissionName)->exists()) {
                    //     Permission::create(['name' => $permissionName]);
                    // } else {
                    //     Log::info("Permission '{$permissionName}' already exists.");
                    // }

                    $permission = Permission::firstOrCreate(['name' => $permissionName]);
                    !$permission->wasRecentlyCreated? Log::info("Permission '{$permissionName}' already exists."):'';
                }

                $role = Role::where('name','admin')->first();
                $role->syncPermissions($permissionsData);
            });
        } catch (\Exception $e) {
            Log::error("Failed to create permissions: " . $e->getMessage());
        }
    }
}
