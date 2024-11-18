<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontendCustomContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 300) as $i) {

            if ($i >= 121 && $i <= 150) {
                $custom_content_id = 2;
            } elseif ($i >= 151 && $i <= 190) {
                $custom_content_id = 3;
            } elseif ($i >= 191 && $i <= 220) {
                $custom_content_id = 4;
            } elseif ($i >= 221 && $i <= 249) {
                $custom_content_id = 5;
            } elseif ($i >= 250 && $i <= 300) {
                $custom_content_id = 6;
            } else {
                $custom_content_id = 1;
            } 

         DB::table('frontend_custom_contents')->insert([
                'content_id' => $i,
                'publish_date' => '2022-07-21 12:45:31',
                'is_active' => true,
                'sorting_position' => 1,
                'frontend_custom_content_type_id' => $custom_content_id,
            ]); 
            
        }
    }
}
