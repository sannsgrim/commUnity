<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $adminRole = Role::where('name', 'admin')->first();
        $adminPermissions = $adminRole->permissions;

        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'first_name' => 'Admin' . $i,
                'last_name' => 'User',
                'email' => 'admin' . $i . '@example.com',
                'password' => bcrypt('password'),
            ])->assignRole('admin');

            Admin::create([
                'user_id' => $user->id,
                'username' => 'adminuser' . $i,
            ]);

            // Select a random subset of permissions
            $randomPermissions = $adminPermissions->random(rand(1, $adminPermissions->count()));
            $user->givePermissionTo($randomPermissions);
        }

    }
}
