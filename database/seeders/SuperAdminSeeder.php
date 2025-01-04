<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        $superAdmin = SuperAdmin::create([
            'user_id' => $user->id,
            'username' => 'superadminuser',
        ]);

        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $user->assignRole($role);
    }
}
