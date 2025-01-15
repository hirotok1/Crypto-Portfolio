<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class SwapsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('swaps')->insert([
            'user_id' => '1',
            'place' => 'Bitbank',
            'coina' => 'BTC',
            'amounta' => '0.01',
            'coinb' => 'JPY',
            'amountb' => '160000',
            'customfeecoin' => 'JPY',
            'customfee' => '40',                    
            'customtime' => '2025-01-12 13:41:21',
            'memo' => 'second swap',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
