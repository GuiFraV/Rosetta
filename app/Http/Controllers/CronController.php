<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CronController extends Controller
{    
    /**
    * Cron task for the Road duplicates.
    *
    * @return void
    */
    public function decreaseRoadCounter()
    {
        $res = DB::update('UPDATE trajets SET visible = visible-1 WHERE visible >= 0');
        return 0;                   
    }
}
