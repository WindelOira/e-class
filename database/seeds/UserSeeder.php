<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'employee_id'   => 1,
            'role'          => 'administrator',
            'name'          => 'Admin',
            'email'         => 'admin@cefi.edu.ph',
            'password'      => bcrypt('admin')
        ]);
    }
}
