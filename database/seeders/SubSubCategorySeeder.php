<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1; $i <= 4; $i++) {
            $root_category_id = $i;
            $sub_category_id = SubCategory::where('root_category_id', $root_category_id)->pluck('id')->toArray();
            for ($j = 0; $j < count($sub_category_id); $j++) {
                DB::table('sub_sub_categories')->insert([
                    'title' => 'Trending',
                    'slug' => Str::uuid('Trending'),
                    'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                    'order' => 1,
                    'root_category_id' => $root_category_id,
                    'sub_category_id' => $sub_category_id[$j],
                    'seo_title' => 'Trending',
                    'seo_description' => 'Trending',
                    'created_at' => Carbon::now(),
                    'status' => 'Published',
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('sub_sub_categories')->insert([
                    'title' => 'Most Popular',
                    'slug' => Str::uuid('Most Popular'),
                    'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                    'order' => 1,
                    'root_category_id' => $root_category_id,
                    'sub_category_id' => $sub_category_id[$j],
                    'seo_title' => 'Most Popular',
                    'seo_description' => 'Most Popular',
                    'created_at' => Carbon::now(),
                    'status' => 'Published',
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('sub_sub_categories')->insert([
                    'title' => 'Romantic',
                    'slug' => Str::uuid('Romantic'),
                    'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                    'order' => 1,
                    'root_category_id' => $root_category_id,
                    'sub_category_id' => $sub_category_id[$j],
                    'seo_title' => 'Romantic',
                    'seo_description' => 'Romantic',
                    'created_at' => Carbon::now(),
                    'status' => 'Published',
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('sub_sub_categories')->insert([
                    'title' => 'Action',
                    'slug' => Str::uuid('Action'),
                    'image' => 'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
                    'order' => 1,
                    'root_category_id' => $root_category_id,
                    'sub_category_id' => $sub_category_id[$j],
                    'seo_title' => 'Action',
                    'seo_description' => 'Action',
                    'created_at' => Carbon::now(),
                    'status' => 'Published',
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
