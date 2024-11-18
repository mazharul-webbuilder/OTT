<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontendCustomContentSectionSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 36; $i++) {
            if ($i >= 7 && $i <= 12) {
                $frontend_custom_content_type_id = 1;
            } elseif ($i >= 13 && $i <= 18) {
                $frontend_custom_content_type_id = 2;
            } elseif ($i >= 19 && $i <= 24) {

                $frontend_custom_content_type_id = 3;
            } elseif ($i >= 25 && $i <= 30) {

                $frontend_custom_content_type_id = 4;
            } elseif ($i >= 31 && $i <= 36) {
                $frontend_custom_content_type_id = 5;
            } else {
                $frontend_custom_content_type_id = 6;
            }
            $j = rand(1, 8);
            DB::table('frontend_custom_content_section_sliders')->insert([
                'title' => "{$j} Test",
                'image' => "https://static.durbar.live/ott/images/slider/slider-{$j}.jpg",
                'content_url' => "http://159.223.86.243/storage/videos/Vedio_{$j}.mp4",
                'order' => 1,
                'status' => 'Published',
                'frontend_custom_content_type_id' => $frontend_custom_content_type_id,
            ]);
        }
    }
}
