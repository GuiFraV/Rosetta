<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'active' => '0',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('pass@admin'),
        ]);
        
        DB::table('users')->insert([
            'role_id' => '2',
            'active' => '1',
            'email' => 'user@gmail.com',
            'password' => bcrypt('pass@user'),
        ]);
        
        DB::table('users')->insert([
            'role_id' => '3',
            'active' => '0',
            'email' => 'manager1@gmail.com',
            'password' => bcrypt('pass@manager1'),
        ]);
        DB::table('users')->insert([
            'role_id' => '3',
            'active' => '1',
            'email' => 'manager2@gmail.com',
            'password' => bcrypt('pass@manager2'),
        ]);
        DB::table('managers')->insert([
            'first_name' => 'F Test 1',
            'last_name' => 'L Test 1',
            'user_id' => '3',
            'logo' => 'logo.png',
            'type' => 'LM',
            'signature' => 'aaaaa1',
        ]);
        DB::table('managers')->insert([
            'first_name' => 'F Test 2',
            'last_name' => 'L Test 3',
            'user_id' => '4',
            'logo' => 'logo.png',
            'type' => 'TM',
            'signature' => 'aaaaa2',
        ]);
    }
}
