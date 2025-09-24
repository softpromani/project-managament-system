<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'create-project',
            'read-project',
            'update-project',
            'delete-project',
            'create-task',
            'read-task',
            'update-task',
            'delete-task',
            'create-comment',
            'read-comment',
            'update-comment',
            'delete-comment',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'read-project',
            'create-task',
            'read-task',
            'update-task',
            'delete-task',
            'create-comment',
            'read-comment',
        ]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'read-project',
            'read-task',
            'update-task', // only assigned tasks
            'create-comment',
            'read-comment',
        ]);
    }
}
