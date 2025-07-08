<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'id' => 1,
            'name' => 'Admin Master',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('bundamole'), // senha: admin123
            'phone' => '+55 11 99999-0001',
            'type' => 'admin',
            'status' => 'active',
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Customer
        User::create([
            'id' => 2,
            'name' => 'Cliente Teste',
            'email' => 'customer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('bundamole'), // senha: cliente123
            'phone' => '+55 11 98888-0002',
            'type' => 'customer',
            'status' => 'active',
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
