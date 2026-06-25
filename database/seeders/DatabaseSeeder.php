<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Jalankan Role & Permission Seeder
        $this->call(RolePermissionSeeder::class);

        // Jalankan Kategori Seeder
        $this->call(CategorySeeder::class);

        // Jalankan Destinasi Seeder
        $this->call(DestinationSeeder::class);

        // Buat Akun Admin
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Buat Akun User Biasa
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole('user');
    }
}
