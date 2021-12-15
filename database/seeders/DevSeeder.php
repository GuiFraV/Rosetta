<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => "3",
            'active' => "1",
            'email' => "developpement2@intergate-logistic.com",
            'email_verified_at' => NULL,
            'password' => "$2y$10$0eABMjUceh0F7gqgCapBXuUYxKOCxnuHqo9YXpBXgQjsklII1w.IG",
            'remember_token' => NULL,
            'created_at' => NULL,
            'updated_at' => NULL
        ]);

        Manager::create([
            'first_name' => "dev",
            'last_name' => "dev", 
            'user_id' => 1,
            'logo' => "logo.png",
            'type' => "TM",
            'signature' => "Regards, dev.",
            'agency_id' => 1
        ]);
    }
}
