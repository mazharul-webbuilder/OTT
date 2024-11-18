<?php

namespace App\Console\Commands;

use App\Models\OttContent;
use Illuminate\Console\Command;
use Elasticsearch;
use Exception;

class IndexDleteOttContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:contents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $contents = OttContent::all();

        foreach ($contents as $content) {
        try {
                Elasticsearch::delete([
                    'id' => $content->id,
                    'index' => 'ott_contents',
                    'type'=>'_doc',
                    // 'body' => [
                    
                    //     'uuid' => $content->uuid,
                    //     'title' => $content->title,
                    //     'short_title' => $content->short_title,
                    //     'description' => $content->description,
                    //     'description' => $content->description,
                    //     'year' => $content->year,
                    //     'root_category' => $content->rootCategory->title,
                    // ]
                ]);
                } catch (Exception $e) {
                    $this->info($e->getMessage());
                }
        }
    
        $this->info("Contents were removed successfully ");
    }
}
