<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 5 Masyarakat
        $masyarakat = ['Doni', 'Anto', 'Erik', 'Azzam', 'Adin'];

        foreach ($masyarakat as $name) {
            User::create([
                'name' => $name,
                'email' => strtolower($name).'@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
            ]);
        }

        // 5 Pemerintah
        $pemerintah = ['AdminJAKUT', 'AdminSULSEL', 'AdminJAKTIM', 'AdminJATIM', 'AdminBALI'];

        foreach ($pemerintah as $name) {
            User::create([
                'name' => $name,
                'email' => strtolower($name).'@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pemerintah',
            ]);
        }
    }
}
