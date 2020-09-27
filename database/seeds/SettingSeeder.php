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
            ['key' => 'transmuted_grades', 'value' => json_encode([
                ['from' => 0, 'to' => 3.99, 'grade' => 60],
                ['from' => 4, 'to' => 7.99, 'grade' => 610],
                ['from' => 8, 'to' => 11.99, 'grade' => 62],
                ['from' => 12, 'to' => 15.99, 'grade' => 63],
                ['from' => 16, 'to' => 19.99, 'grade' => 64],
                ['from' => 20, 'to' => 23.99, 'grade' => 65],
                ['from' => 24, 'to' => 27.99, 'grade' => 66],
                ['from' => 28, 'to' => 31.99, 'grade' => 67],
                ['from' => 32, 'to' => 35.99, 'grade' => 68],
                ['from' => 36, 'to' => 39.99, 'grade' => 69],
                ['from' => 40, 'to' => 43.99, 'grade' => 70],
                ['from' => 44, 'to' => 47.99, 'grade' => 71],
                ['from' => 48, 'to' => 51.99, 'grade' => 72],
                ['from' => 52, 'to' => 55.99, 'grade' => 73],
                ['from' => 56, 'to' => 59.99, 'grade' => 74],
                ['from' => 60, 'to' => 61.59, 'grade' => 75],
                ['from' => 61.6, 'to' => 63.19, 'grade' => 76],
                ['from' => 63.2, 'to' => 64.79, 'grade' => 77],
                ['from' => 64.8, 'to' => 66.39, 'grade' => 78],
                ['from' => 66.4, 'to' => 67.99, 'grade' => 79],
                ['from' => 68, 'to' => 69.59, 'grade' => 80],
                ['from' => 69.6, 'to' => 71.19, 'grade' => 81],
                ['from' => 71.2, 'to' => 72.79, 'grade' => 82],
                ['from' => 72.8, 'to' => 74.39, 'grade' => 83],
                ['from' => 74.4, 'to' => 75.99, 'grade' => 84],
                ['from' => 76, 'to' => 77.59, 'grade' => 85],
                ['from' => 77.6, 'to' => 79.19, 'grade' => 86],
                ['from' => 79.2, 'to' => 80.79, 'grade' => 87],
                ['from' => 80.8, 'to' => 82.39, 'grade' => 88],
                ['from' => 82.4, 'to' => 83.99, 'grade' => 89],
                ['from' => 84, 'to' => 85.59, 'grade' => 90],
                ['from' => 85.6, 'to' => 87.19, 'grade' => 91],
                ['from' => 87.2, 'to' => 88.79, 'grade' => 92],
                ['from' => 88.8, 'to' => 90.39, 'grade' => 93],
                ['from' => 90.4, 'to' => 91.99, 'grade' => 94],
                ['from' => 92, 'to' => 93.59, 'grade' => 95],
                ['from' => 93.6, 'to' => 95.19, 'grade' => 96],
                ['from' => 95.2, 'to' => 95.19, 'grade' => 97],
                ['from' => 96.8, 'to' => 96.79, 'grade' => 98],
                ['from' => 98.4, 'to' => 98.39, 'grade' => 99],
                ['from' => 100, 'to' => 100, 'grade' => 100],
            ])
            ]
        ]);
    }
}
