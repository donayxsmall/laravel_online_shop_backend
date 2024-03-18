<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Donny Kusdianto',
            'email' => 'donayxsmall@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '0895342414096',
            'roles' => 'ADMIN',
        ]);
    }
}
