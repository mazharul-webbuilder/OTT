<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Support\MediaOpenerFactory;

class VideoEncode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video-encode:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'video encoding';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
        $highFormat = (new X264('aac'))->setKiloBitrate(1000);
        
        $this->info("Converting Video");
        FFMpeg::fromDisk('spaces')
                // ->open('video.mp4')
                ->open('OTT/Content/there-was/Trailer/video/tK8x6vf0Q1An7vJLunbSsCqbhnFykgNjnhiHhBhL.mp4')
                ->exportForHLS()
                ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                    $filters->resize(1280, 720);
                })
                ->addFormat($highFormat)
                ->onProgress(function ($progress) {
                    $this->info("Progress: {$progress}%");
                })
                ->toDisk('spaces')
                ->save('OTT/Content/there-was/Trailer/video/video.m3u8');
        $this->info('Done!');
    }
}
