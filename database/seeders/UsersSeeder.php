<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'username' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory(10)->create();
    }
}
