<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'kedaijuice123@gmail.com'],
            [
                'name' => 'Admin Al Afghani Juice',
                'password' => Hash::make('juiceenak321'),
                'role' => 'admin', // atau 'cashier'
            ]
        );
    }
}