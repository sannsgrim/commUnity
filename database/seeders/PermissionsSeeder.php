<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions Super Admin
        Permission::create(['name' => 'Can Create Account']);
        Permission::create(['name' => 'Can Delete Account']);
        Permission::create(['name' => 'Can Edit Account']);

        // Create permissions  Admin and Super Admin
        Permission::create(['name' => 'Can Create Post']);
        Permission::create(['name' => 'Can Delete Post']);
        Permission::create(['name' => 'Can Edit Post']);

        // Create permissions User, Admin and Super Admin
        Permission::create(['name' => 'Can View Post']);
        Permission::create(['name' => 'Can View Account']);
        Permission::create(['name' => 'Can Create Own Post']);
        Permission::create(['name' => 'Can Delete Own Post']);
        Permission::create(['name' => 'Can Edit Own Post']);
        Permission::create(['name' => 'Can Comment Own Post']);
        Permission::create(['name' => 'Can Comment Others Post']);

    }
}
