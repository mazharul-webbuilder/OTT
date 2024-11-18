<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OttSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 32; $i++) {
            if ($i >= 9 && $i <= 16) {
                $is_home = false;
                $root_category_id = 1;
            } elseif ($i >= 17 && $i <= 24) {
                $is_home = false;
                $root_category_id = 2;
            } elseif ($i >= 25 && $i <= 32) {
                $is_home = false;
                $root_category_id = 3;
            }  else {
                $root_category_id = 1;
                $is_home = true;
            }
            $j = rand(1, 8);
            DB::table('ott_sliders')->insert([
                'title' => "{$j} Test",
                'slug' => Str::slug("{$j} Test"),
                'description' => "Description {$j}",
                'bottom_title' => "Coming Soon",
                'image' => "https://static.durbar.live/ott/images/slider/slider-{$j}.jpg",
                'content_url' => "http://159.223.86.243/storage/videos/Vedio_{$j}.mp4",
                'order' => 1,
                'status' => 'Published',
                'root_category_id' => $root_category_id,
                'is_home' => $is_home
            ]);
        }
    }
}
