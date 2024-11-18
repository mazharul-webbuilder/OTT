<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //category-1
        DB::table('sub_categories')->insert([
            'title' => 'Dhallywood',
            'slug' => Str::slug('Dhallywood'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 1,
            'seo_title' => 'Dhallywood',
            'seo_description' => 'Dhallywood',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sub_categories')->insert([
            'title' => 'Tamil',
            'slug' => Str::slug('Tamil'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 1,
            'seo_title' => 'Tamil',
            'seo_description' => 'Tamil',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sub_categories')->insert([
            'title' => 'Hindi',
            'slug' => Str::slug('Hindi'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 1,
            'seo_title' => 'Hindi',
            'seo_description' => 'Hindi',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
        DB::table('sub_categories')->insert([
            'title' => 'English',
            'slug' => Str::slug('English'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 1,
            'seo_title' => 'English',
            'seo_description' => 'English',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
        //category-2
        DB::table('sub_categories')->insert([
            'title' => 'Recommended Live',
            'slug' => Str::slug('Recommended Live'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 2,
            'seo_title' => 'Recommended Live',
            'seo_description' => 'Recommended Live',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
        //category-2
        DB::table('sub_categories')->insert([
            'title' => 'News Inside The Country',
            'slug' => Str::slug('News Inside The Country'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 2,
            'seo_title' => 'News Inside The Country',
            'seo_description' => 'News Inside The Country',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
        //category-2
        DB::table('sub_categories')->insert([
            'title' => 'Live Sports',
            'slug' => Str::slug('Live Sports'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 2,
            'seo_title' => 'Live Sports',
            'seo_description' => 'Live Sports',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        //category-4
        DB::table('sub_categories')->insert([
            'title' => 'THRILLER SERIES',
            'slug' => Str::slug('THRILLER SERIES'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 2,
            'root_category_id' => 3,
            'seo_title' => 'THRILLER SERIES',
            'seo_description' => 'THRILLER SERIES',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sub_categories')->insert([
            'title' => 'Comedy series',
            'slug' => Str::slug('Comedy series'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 3,
            'seo_title' => 'Comedy series',
            'seo_description' => 'Comedy series',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sub_categories')->insert([
            'title' => 'Drama',
            'slug' => Str::slug('Drama'),
            'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'order' => 1,
            'root_category_id' => 3,
            'seo_title' => 'Drama',
            'seo_description' => 'Drama',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sub_categories')->insert([
            'title' => 'Bangladesh Away Series',
            'slug' => Str::slug('Bangladesh Away Series'),
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/series/1655377286_sa-v-ban-series.jpg',
            'order' => 3,
            'root_category_id' => 1,
            'seo_title' => 'Bangladesh Away Series',
            'seo_description' => 'Bangladesh Away Series',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
       
        //12
        DB::table('sub_categories')->insert([
            'title' => 'ZEE NETWORKS',
            'slug' => Str::slug('ZEE NETWORKS'),
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/series/1655377286_sa-v-ban-series.jpg',
            'order' => 3,
            'root_category_id' => 4,
            'seo_title' => 'ZEE NETWORKS',
            'seo_description' => 'ZEE NETWORKS',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        //13
        DB::table('sub_categories')->insert([
            'title' => 'News',
            'slug' => Str::slug('News'),
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/series/1655377286_sa-v-ban-series.jpg',
            'order' => 3,
            'root_category_id' => 4,
            'seo_title' => 'News',
            'seo_description' => 'News',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);

        //14
        DB::table('sub_categories')->insert([
            'title' => 'Entertainment',
            'slug' => Str::slug('Entertainment'),
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/series/1655377286_sa-v-ban-series.jpg',
            'order' => 3,
            'root_category_id' => 4,
            'seo_title' => 'Entertainment',
            'seo_description' => 'Entertainment',
            'created_at' => Carbon::now(),
            'status' => 'Published',
            'updated_at' => Carbon::now(),
        ]);
       
    }
}
