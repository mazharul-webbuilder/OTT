<?php

namespace Database\Seeders;

use App\Enums\OttContentEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OttContentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //14
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Asia Cup 2022 Live',
            'short_title' => 'Asia Cup 2022 Live',
            'root_category_id' => 2,
            'sub_category_id' => 5,
            'content_type_id' => 1,
            'description' => 'The 2022 Asia Cup is the 15th edition of the Asia Cup cricket tournament, with the matches being played as Twenty20 Internationals during August and September 2022 in the United Arab Emirates.

                                MatchIndia vs Pakistan, Super Four, Match 2
                                VenueDubai International Cricket Stadium, Dubai
                                Start Time8:00 PM
                                ParticipatesAfghanista, Bangladesh, India, Pakistan, Sri Lanka, Hong Kong
                                FormatTwenty20 International
                                AdministratorAsian Cricket Council',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        //15
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Premier League Live',
            'short_title' => 'Premier League Live',
            'root_category_id' => 2,
            'sub_category_id' => 7,
            'content_type_id' => 1,
            'description' => 'The Premier League, is the top level of the English football league system. Contested by 20 clubs, it operates on a system of promotion and relegation with the English Football League.

                                MatchManchester United vs Arsenal
                                Total Matchs380
                                Clubs20',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //16
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Serie A Live',
            'short_title' => 'Serie A Live',
            'root_category_id' => 2,
            'sub_category_id' => 7,
            'content_type_id' => 1,
            'description' => "The Serie is a professional league competition for football clubs located at the top of the Italian football league system and the winner is awarded the Scudetto and the Coppa Campioni d'Italia.

                                MatchUdinese vs Roma
                                Clubs20
                                Total Match380",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //17
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Serie A Live',
            'short_title' => 'Serie A Live',
            'root_category_id' => 2,
            'sub_category_id' => 7,
            'content_type_id' => 1,
            'description' => "The Serie is a professional league competition for football clubs located at the top of the Italian football league system and the winner is awarded the Scudetto and the Coppa Campioni d'Italia.

                                MatchUdinese vs Roma
                                Clubs20
                                Total Match380",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //18
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Zee Bangla',
            'short_title' => 'Zee Bangla',
            'root_category_id' => 4,
            'sub_category_id' => 12,
            'content_type_id' => 1,
            'description' => "Zee Bangla is one of the most popular and largest Bangla language television channels in India. The channel was launched on 15 September 1999. The headquarter of the channel is located at Kolkata, West Bengal, India. The channel is owned by Zee Entertainment Enterprises. It was previously known as Alpha Bangla. The entertainment-based channel features Tv serials, shows, Bangla movies. entertainment news and so much more.",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //19
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Zee Cinema HD',
            'short_title' => 'Zee Cinema HD',
            'root_category_id' => 4,
            'sub_category_id' => 12,
            'content_type_id' => 1,
            'description' => "Zee Cinema HD is one of the most popular and largest Bangla language television channels in India. The channel was launched on 15 September 1999. The headquarter of the channel is located at Kolkata, West Bengal, India. The channel is owned by Zee Entertainment Enterprises. It was previously known as Alpha Bangla. The entertainment-based channel features Tv serials, shows, Bangla movies. entertainment news and so much more.",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //20
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Independent TV',
            'short_title' => 'Independent TV',
            'root_category_id' => 4,
            'sub_category_id' => 13,
            'content_type_id' => 1,
            'description' => "Independent TV is one of the most popular and largest Bangla language television channels in India. The channel was launched on 15 September 1999. The headquarter of the channel is located at Kolkata, West Bengal, India. The channel is owned by Zee Entertainment Enterprises. It was previously known as Alpha Bangla. The entertainment-based channel features Tv serials, shows, Bangla movies. entertainment news and so much more.",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //21
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'Somoy TV',
            'short_title' => 'Somoy TV',
            'root_category_id' => 4,
            'sub_category_id' => 13,
            'content_type_id' => 1,
            'description' => "Somoy TV is one of the most popular and largest Bangla language television channels in India. The channel was launched on 15 September 1999. The headquarter of the channel is located at Kolkata, West Bengal, India. The channel is owned by Zee Entertainment Enterprises. It was previously known as Alpha Bangla. The entertainment-based channel features Tv serials, shows, Bangla movies. entertainment news and so much more.",
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/channel_poster/1661693454_Asia-Cup-2880-1320-28-08-22.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
