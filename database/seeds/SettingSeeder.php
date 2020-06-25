<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'school_id', 'value' => '403239'],
            ['key' => 'school_name', 'value' => 'Calayan Educational Foundation, Inc.'],
            ['key' => 'region', 'value' => 'IV'],
            ['key' => 'division', 'value' => 'Lucena'],
            ['key' => 'address', 'value' => ''],
            ['key' => 'status', 'value' => 1],
            ['key' => 'mission', 'value' => ''],
            ['key' => 'vision', 'value' => ''],
        ]);
    }
}
