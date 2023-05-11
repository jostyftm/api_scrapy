<?php

namespace App\Console\Commands;

use App\Models\Search;
use App\Models\Spider;
use Illuminate\Console\Command;

class StartSerach extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start webscrapinf search resources';

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
        Search::whereHas('website.webConfiguration',function($q){
            $q->where('state', '=', true);
        })->with('website.webConfiguration')
        ->get()->each(function(Search $search) { 
            $scrapy = new Spider($search);
            $scrapy->run();
        });
    }
}
