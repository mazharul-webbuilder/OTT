<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OttSeriesContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          
        //Syndicate Series 
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'সিন্ডিকেট 1',
            'short_title' => 'সিন্ডিকেট 1',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 1,
            'description' => 'সিন্ডিকেট 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | সিন্ডিকেট 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'সিন্ডিকেট 2',
            'short_title' => 'সিন্ডিকেট 2',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 2,
            'description' => 'সিন্ডিকেট 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | সিন্ডিকেট 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'সিন্ডিকেট 3',
            'short_title' => 'সিন্ডিকেট 3',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 3,
            'description' => 'সিন্ডিকেট 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | সিন্ডিকেট 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'সিন্ডিকেট 4',
            'short_title' => 'সিন্ডিকেট 4',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 4,
            'description' => 'সিন্ডিকেট 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | সিন্ডিকেট 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'সিন্ডিকেট 5',
            'short_title' => 'সিন্ডিকেট 5',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 5,
            'description' => 'সিন্ডিকেট 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | সিন্ডিকেট 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        //Pett kata shaw horror series
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ষ 1',
            'short_title' => 'ষ 1',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>1,
            'content_type_id' => 2,
            'series_order' => 1,
            'description' => 'ষ 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | ষ 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ষ 2',
            'short_title' => 'ষ 2',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>2,
            'content_type_id' => 2,
            'series_order' => 2,
            'description' => 'ষ 2 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | ষ 2 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ষ 3',
            'short_title' => 'ষ 3',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>2,
            'content_type_id' => 2,
            'series_order' => 3,
            'description' => 'ষ 3 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | ষ 3 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ষ 4',
            'short_title' => 'ষ 4',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>2,
            'content_type_id' => 2,
            'series_order' => 4,
            'description' => 'ষ 4 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | ষ 4 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        //Shatticup
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'শাটিকাপ 1',
            'short_title' => 'শাটিকাপ 1',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>3,
            'content_type_id' => 2,
            'series_order' => 1,
            'description' => 'শাটিকাপ 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | শাটিকাপ 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'শাটিকাপ 2',
            'short_title' => 'শাটিকাপ 2',
            'root_category_id' => 3,
            'sub_category_id' =>8,
            'series_id' =>3,
            'content_type_id' => 2,
            'series_order' => 2,
            'description' => 'শাটিকাপ 1 | মাটির ব্যাংক | Abir Mirza | Neelanjona Neela
            Matir Bank | শাটিকাপ 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        //Four Tweenty
        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ফোর টোয়েন্টি 1',
            'short_title' => 'ফোর টোয়েন্টি 1',
            'root_category_id' => 3,
            'sub_category_id' =>9,
            'series_id' =>4,
            'content_type_id' => 2,
            'series_order' => 1,
            'description' => 'ফোর টোয়েন্টি 1| Abir Mirza | Neelanjona Neela
            Matir Bank | ফোর টোয়েন্টি 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ott_contents')->insert([
            'uuid' => md5(uniqid(rand(), true)),
            'title' => 'ফোর টোয়েন্টি 2',
            'short_title' => 'ফোর টোয়েন্টি 2',
            'root_category_id' => 3,
            'sub_category_id' =>9,
            'series_id' =>4,
            'content_type_id' => 2,
            'series_order' => 2,
            'description' => 'ফোর টোয়েন্টি 1| Abir Mirza | Neelanjona Neela
            Matir Bank | ফোর টোয়েন্টি 1 অভিনয়েঃ আবির মির্জা, নিলাঞ্জনা নিলা রচনা, চিত্রনাট্য ও পরিচালনায়ঃ শ্রাবণী ফেরদৌস।',
            'year' => '2021',
            'runtime' => '120',
            'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => 'Feb 16, 2021',
            'status' => 'Published',
            'access' => config('constants.OTTCONTENTFREE'),
            'poster' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'backdrop' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521215_matri-bank-1920x800.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
