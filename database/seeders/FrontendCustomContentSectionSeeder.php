<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontendCustomContentSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>1, 
            'content_type_title'=>'Recently Added', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);
        
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>2, 
            'content_type_title'=>'Dhallywood', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>3, 
            'content_type_title'=>'Tamil', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>4, 
            'content_type_title'=>'English', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>5, 
            'content_type_title'=>'Tv Series', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);
        DB::table('frontend_custom_content_sections')->insert([
            'content_type_slug'=>6, 
            'content_type_title'=>'Popular Drama', 
            'more_info_slug' => 'bangladesh-home-series'
        ]);


       
    }
}
