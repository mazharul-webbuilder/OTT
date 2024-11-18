<?php

namespace App\Jobs;

use App\Models\ContentSource;
use App\Models\OttContent;
use Carbon\Carbon;
use FFMpeg\Filters\Video\FrameRateFilter;
// use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Filters\Video\ResizeFilter;
class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OttContent $video, $path)
    {
        $this->video = $video;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $encryptionKey = HLSExporter::generateEncryptionKey();

        // $dynamicDirectoryName = $this->video->id;
        $destination = '/' . $this->video->title . '/' . $this->video->title . 'm3u8';
        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(6000);
        $AudioBitrateFormat  = (new X264)->setAudioKiloBitrate(128);

        // open the uploaded video from the right disk...

        $ffmpeg = FFMpeg::fromDisk('public')
            ->open($this->path);

        
        $exporter = $ffmpeg->exportForHLS()
            ->toDisk('public');

        // Set the desired framerate, e.g., 30 frames per second
        // Set the desired framerate, e.g., 30 frames per second
        // Set the desired framerate, e.g., 30 frames per second

        // Add the bitrate formats
        // $exporter->addFormat($lowBitrateFormat);
        // $exporter->addFormat($midBitrateFormat);
        $exporter->addFormat($lowBitrateFormat);
        $exporter->addFormat($AudioBitrateFormat);

        // $exporter->onProgress(function ($progress) {
        //     $content_source = ContentSource::firstOrNew(
        //         ['ott_content_id' => $this->video->id],
        //         ['source_format' => '720p']
        //     );
        //     ContentSource::where('id', $content_source->id)->update([
        //         'processing_status' => $progress
        //     ]);
        // });

        $exporter->save($this->video->id . '/' . $this->video->id . '.m3u8');
        // update the database so we know the convertion is done!
        // $this->video->update([
        //     'converted_for_streaming_at' => Carbon::now(),
        // ]);
    }
}