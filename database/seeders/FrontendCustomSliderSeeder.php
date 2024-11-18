<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontendCustomSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('frontend_custom_sliders')->insert([
            'slider_type_slug' => 1,
            'slider_type_title' => '1st Test',
            'slider_type_sub_title' => '1st Test is comming home series',
            'press_action_slug' => '/single-ott-content/content0007',
            'press_action_slug_activity' => 'single_content',
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521265_maya-jal-1920x800.jpg',
            'is_active' => true,
            'sorting_order' => 1
        ]);
        DB::table('frontend_custom_sliders')->insert([
            'slider_type_slug' => 1,
            'slider_type_title' => '1st Test',
            'slider_type_sub_title' => '1st Test is comming home series',
            'press_action_slug' => '/single-ott-content/content0007',
            'press_action_slug_activity' => 'single_content',
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521265_maya-jal-1920x800.jpg',
            'is_active' => true,
            'sorting_order' => 1
        ]);
        DB::table('frontend_custom_sliders')->insert([
            'slider_type_slug' => 1,
            'slider_type_title' => '1st Test',
            'slider_type_sub_title' => '1st Test is comming home series',
            'press_action_slug' => '/single-ott-content/content0007',
            'press_action_slug_activity' => 'single_content',
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521265_maya-jal-1920x800.jpg',
            'is_active' => true,
            'sorting_order' => 2
        ]);
        DB::table('frontend_custom_sliders')->insert([
            'slider_type_slug' => 1,
            'slider_type_title' => '1st Test',
            'slider_type_sub_title' => '1st Test is comming home series',
            'press_action_slug' => '/single-ott-content/content0007',
            'press_action_slug_activity' => 'single_content',
            'image' => 'https://didbxtymavoia.cloudfront.net/cms/videos/1654521265_maya-jal-1920x800.jpg',
            'is_active' => true,
            'sorting_order' => 3
        ]);
    }
}
