<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'phone' => '0123456789',
                'tier' => '4'
            ],
        ];
    }
}
