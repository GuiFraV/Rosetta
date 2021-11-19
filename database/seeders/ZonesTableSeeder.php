<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zones')->insert([
            'zone_name' => 'Zone 1 (EST+LV+LT+CZ+SK)',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Zone 2 (DE+AT+IT+BE+NL+LU+SCAND)',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Zone 3 (FR+ES+PT+UK+CH)',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Zone Part Load',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Zone Full Trucks',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Trucks Part Truck',
        ]);
        DB::table('zones')->insert([
            'zone_name' => 'Intergate Truck',
        ]);
    }
}
