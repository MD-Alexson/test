<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('plans')->insert([
            'id' => 0,
            'name' => "14 дней",
            'projects' => 3,
            'susers' => 1000,
            'space' => 1
        ]);
        DB::table('plans')->insert([
            'id' => 1,
            'name' => "Базовый",
            'projects' => 3,
            'susers' => 1000,
            'space' => 1
        ]);
        DB::table('plans')->insert([
            'id' => 2,
            'name' => "Бизнес",
            'projects' => 10,
            'susers' => 5000,
            'space' => 2
        ]);
        DB::table('plans')->insert([
            'id' => 3,
            'name' => "PRO",
            'projects' => 25,
            'susers' => 15000,
            'space' => 5
        ]);
    }
}