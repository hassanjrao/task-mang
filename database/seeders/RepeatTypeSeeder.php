<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepeatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('repeat_types')->insert([
            ['name' => 'Daily', 'interval' => '1 day'],
            ['name' => 'Weekly', 'interval' => '7 days'],
            ['name' => 'Monthly', 'interval' => '1 month'],
            ['name' => 'Yearly', 'interval' => '1 year'],
        ]);
    }
}
