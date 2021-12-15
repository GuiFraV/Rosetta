<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agencies = array (
            [      
                'id' => 1,
                'agency_name' => 'Intergate Logistic',
            ],
            [      
                'id' => 2,
                'agency_name' => 'Intergate Polska',
            ],
            [      
                'id' => 3,
                'agency_name' => 'Intergate Deutschland',
            ],
            [      
                'id' => 4,
                'agency_name' => 'Intergate Shipping',
            ],
            [      
                'id' => 5,
                'agency_name' => 'Intergate Transport',
            ],
            [      
                'id' => 6,
                'agency_name' => 'Intergate Mediterranean',
            ],
            [      
                'id' => 7,
                'agency_name' => 'Intergate Baltic',
            ]
        );
        
        foreach ($agencies as $agency) {
            \App\Models\Agency::create($agency);
        }
        
        
        
        // $count = 0;
        // while ($count < 50) {
        //     DB::table('mails')->insert([
        //     'object' => Str::random(8),
        //     'message' => Str::random(20),
        // ]);
        // $count = $count + 1;
        // }
    }
}
