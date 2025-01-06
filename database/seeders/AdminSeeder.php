<?php

namespace Database\Seeders;

use App\Helper\EncryptionHelper;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
        $passphrase = 'commUnity';
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'first_name' => EncryptionHelper::encrypt('Admin' . $i, $passphrase),
                'last_name' => EncryptionHelper::encrypt('User', $passphrase),
                'email' => EncryptionHelper::encrypt('admin' . $i . '@example.com', $passphrase),
                'email_verified_at' => now(),
                'email_verification_code' => EncryptionHelper::encrypt(fake()->regexify('[A-Za-z0-9]{6}'), $passphrase),
                'email_verification_code_expires_at' => now(),
                'profile_photo_path' => EncryptionHelper::encrypt('profile-picture/default.png', $passphrase),
                'cover_photo_path' => EncryptionHelper::encrypt('cover-photo/default.png', $passphrase),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ])->assignRole('admin');

            Admin::create([
                'user_id' => $user->id,
                'username' => EncryptionHelper::encrypt('adminuser' . $i, $passphrase),
            ]);

            // Select a random subset of permissions
            $randomPermissions = $adminPermissions->random(rand(1, $adminPermissions->count()));
            $user->givePermissionTo($randomPermissions);
        }

    }
}
