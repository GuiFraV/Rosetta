<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Inserting myself in the database in order to dev
        $this->call(DevSeeder::class);

        // $this->call(UsersTableSeeder::class);
     
        /* 
        // Adding the agencies in the DB
        $this->call(AgencySeeder::class);        
        */

        /* 
        // Adding the roles in the DB
        $this->call(RolesTableSeeder::class);
        */

        /* 
        // Adding the zones in the DB
        $this->call(ZonesTableSeeder::class);
        */

        // $this->call(PartnerSeeder::class);
        // $this->call(GroupSeeder::class);
        // $this->call(MailSeeder::class);
    }
}
