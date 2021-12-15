<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Partner;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $count = 0;
        // while ($count < 50) {
        //     DB::table('groups')->insert([
        //     'groupName' => Str::random(8),
        // ]);
        // $count = $count + 1;
        // }

        // factory(Group::class, 10)->create();
        Group::factory()->count(10)->create();
        foreach (Group::all() as $group)
        {
            $partners = \App\Models\Partner::inRandomOrder()->take(rand(1,3))->pluck('id');
            $group->partners()->attach($partners);
        }
    }
}
