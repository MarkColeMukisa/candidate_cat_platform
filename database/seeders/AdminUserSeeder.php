<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'Admin User');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = bcrypt(env('ADMIN_PASSWORD', 'password'));

        // Idempotent: update if the email exists, otherwise create.
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                // The User model casts will hash this automatically.
                'password' => $password,
            ]
        );
    }
}
