<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name'              => 'Nadine Nathania',
            'email'             => 'thaniandnn@gmail.com',
            'password'          => Hash::make('12345'),
            'address'           => 'Alamat contoh untuk Nadine',
            'role'              => 'customer',
            'security_question' => 'Apa makanan favoritmu?',
            'security_answer'   => 'bakso',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        DB::table('users')->insert([
            'name'              => 'Admin Utama',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('admin123'),
            'address'           => 'Office address',
            'role'              => 'admin',
            'security_question' => 'Apa warna favoritmu?',
            'security_answer'   => 'biru',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
