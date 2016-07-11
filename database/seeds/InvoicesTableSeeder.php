<?php

use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('invoices')->insert([
            'id' => 234048,
            'plan_id' => 1,
            'term' => 1
        ]);
        DB::table('invoices')->insert([
            'id' => 234052,
            'plan_id' => 1,
            'term' => 3
        ]);
        DB::table('invoices')->insert([
            'id' => 234053,
            'plan_id' => 1,
            'term' => 6
        ]);
        DB::table('invoices')->insert([
            'id' => 234054,
            'plan_id' => 1,
            'term' => 12
        ]);
        DB::table('invoices')->insert([
            'id' => 234055,
            'plan_id' => 2,
            'term' => 1
        ]);
        DB::table('invoices')->insert([
            'id' => 234056,
            'plan_id' => 2,
            'term' => 3
        ]);
        DB::table('invoices')->insert([
            'id' => 234057,
            'plan_id' => 2,
            'term' => 6
        ]);
        DB::table('invoices')->insert([
            'id' => 234058,
            'plan_id' => 2,
            'term' => 12
        ]);
        DB::table('invoices')->insert([
            'id' => 234059,
            'plan_id' => 3,
            'term' => 1
        ]);
        DB::table('invoices')->insert([
            'id' => 234060,
            'plan_id' => 3,
            'term' => 3
        ]);
        DB::table('invoices')->insert([
            'id' => 234061,
            'plan_id' => 3,
            'term' => 6
        ]);
        DB::table('invoices')->insert([
            'id' => 234062,
            'plan_id' => 3,
            'term' => 12
        ]);
    }
}