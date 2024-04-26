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
        User::create([
            'full_name'     => 'Fiqri Noor Hadi',
            'username'      => 'fiqrinoorhadi',
            'password'      => Hash::make('fiqri12345'),
            'bio'           => 'Saya adalah programer',
            'is_private'    => true
        ]);
        User::create([
            'full_name'     => 'Lutfia Nurul Fauziah',
            'username'      => 'lulu99',
            'password'      => Hash::make('lulu12345'),
            'bio'           => 'Saya adalah guru',
            'is_private'    => true
        ]);
    }
}
