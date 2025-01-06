<?php

namespace Database\Seeders;

use App\Helper\EncryptionHelper;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passphrase = 'commUnity';
        $user = User::create([
            'first_name' => EncryptionHelper::encrypt('Super', $passphrase),
            'last_name' => EncryptionHelper::encrypt('Admin', $passphrase),
            'email' => EncryptionHelper::encrypt('superadmin@example.com', $passphrase),
            'email_verified_at' => now(),
            'email_verification_code' => EncryptionHelper::encrypt(fake()->regexify('[A-Za-z0-9]{6}'), $passphrase),
            'email_verification_code_expires_at' => now(),
            'profile_photo_path' => EncryptionHelper::encrypt('profile-picture/default.png', $passphrase),
            'cover_photo_path' => EncryptionHelper::encrypt('cover-photo/default.png', $passphrase),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('super-admin');

        SuperAdmin::create([
            'user_id' => $user->id,
            'username' => EncryptionHelper::encrypt('superadminuser', $passphrase),
        ]);

    }
}
