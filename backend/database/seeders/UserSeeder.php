<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'a@a.a',
            'password' => password_hash('P@$$w0rd', PASSWORD_DEFAULT),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}