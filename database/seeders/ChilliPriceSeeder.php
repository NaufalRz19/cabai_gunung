<?php

namespace Database\Seeders;

use App\Models\Chilli;
use App\Models\ChilliPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChilliPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChilliPrice::create([
            'chilli_id' => Chilli::first()->id,
            'price' => 15000
        ]);
    }
}
