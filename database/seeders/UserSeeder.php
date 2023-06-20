<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $this->seedAdmin();
        $this->seedDummyUsers();
    }

    private function seedAdmin(): void
    {
        $adminName = env('ADMIN_NAME');
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');


        $user = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
        ]);
    }
    private function seedDummyUsers(): void
    {
        User::factory()->count(50)->create();
    }

}
