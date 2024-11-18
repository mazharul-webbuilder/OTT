<?php

namespace App\Console\Commands;

use App\Models\OttContent;
use Exception;
use Illuminate\Console\Command;

class AddOttContentToIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:add-content-to-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ott content to elastic index ';

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
        try {
            $data =  OttContent::addAllToIndex();
            $this->info('Data added successfully'); 
        } catch (Exception $e) {
            $this->info($e->getMessage());  
        } 
    }
}
