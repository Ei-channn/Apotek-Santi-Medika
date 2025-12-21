<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@apotek.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['name' => 'Kasir'],
            [
                'email' => 'kasir@apotek.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir'
            ]
        );
    }
}
