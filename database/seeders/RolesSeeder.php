<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $superAdminRole->givePermissionTo([
            'Can Create Account',
            'Can Delete Account',
            'Can Edit Account',
            'Can Create Post',
            'Can Delete Post',
            'Can Edit Post',
            'Can View Post',
            'Can View Account',
            'Can Create Own Post',
            'Can Delete Own Post',
            'Can Edit Own Post',
            'Can Comment Own Post',
            'Can Comment Others Post',
        ]);

        $adminRole->givePermissionTo([
            'Can Create Post',
            'Can Delete Post',
            'Can Edit Post',
            'Can View Post',
            'Can View Account',
            'Can Create Own Post',
            'Can Delete Own Post',
            'Can Edit Own Post',
            'Can Comment Own Post',
            'Can Comment Others Post',
        ]);

        $userRole->givePermissionTo([
            'Can View Post',
            'Can View Account',
            'Can Create Own Post',
            'Can Delete Own Post',
            'Can Edit Own Post',
            'Can Comment Own Post',
            'Can Comment Others Post',
        ]);

    }
}
