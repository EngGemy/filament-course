<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //check if an admin user already exists
        if (!DB::table('users')->where('email','admin@gmail.com')->exists()){
            DB::table('users')->insert([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'is_admin' => true,
                "password" => Hash::make('password')
            ]);
        }

    }
}
