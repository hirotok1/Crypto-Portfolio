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
            'user_id' => '1',
            'place_id' => '1',
            'coin' => 'HYPE',
            'amount' => '50',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
    }
}
