<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@gmail.com',
            'password' => 'rahasia',
            'name' => 'admin',
            'status' => 'admin'
        ]);
        User::create([
            'email' => 'retail@gmail.com',
            'password' => 'rahasia',
            'name' => 'retail',
            'status' => 'retail'
        ]);
        User::create([
            'email' => 'petani@gmail.com',
            'password' => 'rahasia',
            'name' => 'petani',
            'status' => 'petani'
        ]);
    }
}
