<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'hiroto',
            'email' => 'trophyvaio1@gmail.com',
            'password' => 'password',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
