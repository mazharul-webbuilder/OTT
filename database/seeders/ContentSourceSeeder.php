<?php

namespace Database\Seeders;

use App\Models\ContentSource;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContentSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ContentSource::factory()->count(780)->create();

        // ContentSource::insert(
        //     [

        //         // Content 1 
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "20",
        //             'source_format' => '144p', 
        //             'created_at' => Carbon::now(),
        //         ],

        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "120",
        //             'source_format' => '240p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "140",
        //             'source_format' => '360p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "240",
        //             'source_format' => '480p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "420",
        //             'source_format' => '720p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 1,
        //             'content_source' => "https://www.youtube.com/watch?v=Gmh_ztxH-uo",
        //             'fps' => "640",
        //             'source_format' => '1080p', 
        //             'created_at' => Carbon::now(),
        //         ],

        //         //Content 2 
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "20",
        //             'source_format' => '144p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "120",
        //             'source_format' => '240p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "140",
        //             'source_format' => '360p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "240",
        //             'source_format' => '480p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "420",
        //             'source_format' => '720p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 2,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "640",
        //             'source_format' => '1080p', 
        //             'created_at' => Carbon::now(),
        //         ],

        //         //Content 3
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "20",
        //             'source_format' => '144p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "120",
        //             'source_format' => '240p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "140",
        //             'source_format' => '360p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "240",
        //             'source_format' => '480p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "420",
        //             'source_format' => '720p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 1,
        //             'ott_content_id' => 3,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "640",
        //             'source_format' => '1080p', 
        //             'created_at' => Carbon::now(),
        //         ],

        //         //Content 4
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "20",
        //             'source_format' => '144p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "120",
        //             'source_format' => '240p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "140",
        //             'source_format' => '360p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "240",
        //             'source_format' => '480p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "420",
        //             'source_format' => '720p', 
        //             'created_at' => Carbon::now(),
        //         ],
        //         [
        //             'subscription_plan_id' => 2,
        //             'ott_content_id' => 4,
        //             'content_source' => "https://www.youtube.com/watch?v=CknubjZVRKM",
        //             'fps' => "640",
        //             'source_format' => '1080p', 
        //             'created_at' => Carbon::now(),
        //         ],


        //     ]
        // );
    }
}
