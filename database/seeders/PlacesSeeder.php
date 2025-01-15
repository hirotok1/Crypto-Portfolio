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
            'name' => 'Coldcard',
            'type' => 'HWW',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
