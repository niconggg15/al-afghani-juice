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
            ['email' => 'alafghanijuice@gmail.com'],
            [
                'name' => 'Admin Al Afghani Juice',
                'username' => null,
                'password' => Hash::make('juiceenak321'),
                'role' => 'admin',
            ]
        );

        if (config('app.demo_account_enabled')) {
            User::updateOrCreate(
                ['email' => 'juiceenak@al-afghani-juice.local'],
                [
                    'name' => 'Akun Analisa Guru',
                    'username' => 'juiceenak',
                    'password' => Hash::make('enakbanget123'),
                    'role' => 'admin',
                ]
            );
        }
    }
}