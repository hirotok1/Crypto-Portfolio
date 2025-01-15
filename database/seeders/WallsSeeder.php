<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class WallsSeeder extends Seeder
{
   
    public function run(): void
    {
        DB::table('walls')->insert([
            'user_id' => '1',
            'name' => 'hiroto',
            'prime' => '1',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
