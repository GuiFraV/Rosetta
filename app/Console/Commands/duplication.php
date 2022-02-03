<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class duplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplication:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the route duplication and hides every unduplicated load or truck.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $res = DB::update('UPDATE trajets SET visible = visible-1 WHERE visible >= 0');
        $this->info("Number of routes updated : " . $res);
    }
}
