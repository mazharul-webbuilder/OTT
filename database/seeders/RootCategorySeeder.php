<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RootCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('root_categories')->insert([
            [
                'title' => 'Movies',
                'slug' => Str::slug('Movies'),
                'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                'order' => 1,
                'seo_title' => 'Movies',
                'seo_description' => 'Movies',
                'is_fixed' => 1,
                'created_at' => Carbon::now(),
                'status' => 'Published',
                'updated_at' => Carbon::now(),

            ],[
                'title' => 'Live',
                'slug' => Str::slug('Live'),
                'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                'order' => 1,
                'seo_title' => 'Live',
                'seo_description' => 'Live',
                'is_fixed' => 0,
                'created_at' => Carbon::now(),
                'status' => 'Published',
                'updated_at' => Carbon::now(),

            ],[
                'title' => 'TV Shows',
                'slug' => Str::slug('TV Shows'),
                'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                'order' => 1,
                'seo_title' => 'TV Shows',
                'seo_description' => 'TV Shows',
                'is_fixed' => 1,
                'created_at' => Carbon::now(),
                'status' => 'Published',
                'updated_at' => Carbon::now(),

            ],[
                'title' => 'TV',
                'slug' => Str::slug('TV'),
                'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                'order' => 1,
                'seo_title' => 'TV',
                'seo_description' => 'TV',
                'is_fixed' => 1,
                'created_at' => Carbon::now(),
                'status' => 'Published',
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
