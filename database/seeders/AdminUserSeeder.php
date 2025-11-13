<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'Admin User');
        // Use a gmail.com address by default to satisfy canAccessPanel()
        $email = env('ADMIN_EMAIL', 'admin@gmail.com');
        // Rely on the User model's 'hashed' cast to hash the password
        $password = env('ADMIN_PASSWORD', 'password');

        // Idempotent: update if the email exists, otherwise create.
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                // Mark email as verified so hasVerifiedEmail() passes
                'email_verified_at' => now(),
            ]
        );
    }
}
