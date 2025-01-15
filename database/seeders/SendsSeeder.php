<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class SendsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sends')->insert([
            'user_id' => '1',
            'coin' => 'BTC',
            'placea' => 'Bitbank',
            'amounta' => '0.01',
            'placeb' => 'Coldcard',
            'amountb' => '0.00999',
            /*'customfeecoin' => '0.00999',
            'customfee' => '0.00999',*/
            'customtime' => '2025-01-10 03:30:21',
            'memo' => 'tired.4am.',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
