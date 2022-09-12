<?php

namespace Database\Seeders;

use App\Models\Chilli;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChilliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chilli::create([
            'type_of_chilli' => 'Chilli 1',
            'fee' => '5000',
        ]);
    }
}
