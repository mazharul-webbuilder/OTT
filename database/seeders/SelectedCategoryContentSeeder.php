<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SelectedCategoryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('selected_category_contents')->insert([
            'root_category_id'=>2,
            'ott_content_id'=> 2,
            'is_featured'=> true, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

        DB::table('selected_category_contents')->insert([
            'root_category_id'=>2,
            'ott_content_id'=> 1,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

        DB::table('selected_category_contents')->insert([
            'root_category_id'=>2,
            'ott_content_id'=> 3,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

        DB::table('selected_category_contents')->insert([
            'root_category_id'=>1,
            'ott_content_id'=>4,
            'is_featured'=> true, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

        DB::table('selected_category_contents')->insert([
            'root_category_id'=>1,
            'ott_content_id'=>9,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);
        DB::table('selected_category_contents')->insert([
            'root_category_id'=>1,
            'ott_content_id'=>18,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);
        DB::table('selected_category_contents')->insert([
            'root_category_id'=>1,
            'ott_content_id'=>23,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);


        DB::table('selected_category_contents')->insert([
            'root_category_id'=>1,
            'ott_content_id'=>24,
            'is_featured'=> false, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);
        DB::table('selected_category_contents')->insert([
            'root_category_id'=>3,
            'ott_content_id'=>26,
            'is_featured'=> true, 
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

    }
}
