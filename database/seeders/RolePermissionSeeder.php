<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Destinations
        $destinationPermissions = [
            'view-destinations',
            'create-destinations',
            'edit-destinations',
            'delete-destinations',
        ];

        // Create permissions for Categories
        $categoryPermissions = [
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
        ];

        // Create permissions for Dashboard & Users
        $otherPermissions = [
            'view-dashboard',
            'manage-users',
        ];

        // Create all permissions
        foreach (array_merge($destinationPermissions, $categoryPermissions, $otherPermissions) as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Admin role with all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create Content Manager role
        $contentManagerRole = Role::create(['name' => 'content_manager']);
        $contentManagerRole->givePermissionTo([
            // Full CRUD on Destinations
            'view-destinations',
            'create-destinations',
            'edit-destinations',
            'delete-destinations',
            // Only Create & Read on Categories (no edit/delete)
            'view-categories',
            'create-categories',
            // Dashboard access
            'view-dashboard',
        ]);

        // Create User role (public viewing only)
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view-destinations',
            'view-categories',
        ]);

        $this->command->info('Roles and Permissions created successfully!');
        $this->command->info('- Admin: Full access to all features');
        $this->command->info('- Content Manager: CRUD destinations, CR categories, dashboard access');
        $this->command->info('- User: View destinations and categories only');
    }
}
