<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::firstOrCreate(['name' => 'Low'], ['name' => 'Low']);
        Priority::firstOrCreate(['name' => 'Medium'], ['name' => 'Medium']);
        Priority::firstOrCreate(['name' => 'High'], ['name' => 'High']);
        Priority::firstOrCreate(['name' => 'Critical'], ['name' => 'Critical']);
    }
}
