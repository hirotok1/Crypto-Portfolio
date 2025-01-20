<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PlacesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('places')->insert([
            'user_id' => '3',
            'place' => 'Hyperliquid',
            'type' => 'exchange',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
