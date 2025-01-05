<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'email_verification_code' => fake()->regexify('[A-Za-z0-9]{6}'),
            'email_verification_code_expires_at' => now(),
            'profile_photo_path' => 'profile-picture/default.png',
            'cover_photo_path' => 'cover-photo/default.png',
            'password' => static::$password ??= Hash::make('Password@123'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('user');

            // Assign specified permissions to the user
            $permissions = [
                'Can View Post',
                'Can View Account',
                'Can Create Own Post',
                'Can Delete Own Post',
                'Can Edit Own Post',
                'Can Comment Own Post',
                'Can Comment Others Post',
            ];

            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
            }
        });
    }
}
