<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin=Role::firstOrCreate(['name'=>'admin'],['name'=>'Admin']);
        $roleStudent=Role::firstOrCreate(['name'=>'student'],['name'=>'Student']);
        $roleWorker=Role::firstOrCreate(['name'=>'worker'],['name'=>'Worker']);
    }
}
