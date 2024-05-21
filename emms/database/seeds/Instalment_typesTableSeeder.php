<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Instalment_typesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instalment_types')->insert([
            [
                'name'=>'daily'
            ],

            [
                'name'=>'weekly'
            ],
            [
                'name'=>'half-monthly'
            ],

            [
                'name'=>'monthly'
            ],
            [
                'name'=>'quarterly'
            ],
            [
                'name'=>'half-yearly'
            ],

            [
                'name'=>'yearly'
            ]


        ]);
    }
}
