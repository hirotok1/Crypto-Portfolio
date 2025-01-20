<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PortfoliosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('portfolios')->insert([
            'user_id' => '3',
            'place_id' => '1',
            'coin' => 'TRUMP',
            'amount' => '10',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
    }
}
