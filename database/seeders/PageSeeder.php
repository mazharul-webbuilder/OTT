<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'title'=>'Privacy Policy',
            'slug'=>Str::slug('Privacy Policy'),
            'thumbnail'=>'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'short_description'=> 'privacypolicy',
            'content'=>"1.PRIVACY
            Please review our Privacy Agreement, which also governs your visit to the Site. The personal information / data provided to us by you or your use of the Site will be treated as strictly confidential, in accordance with the Privacy Agreement and applicable laws and regulations. If you object to your
            information being transferred or used in the manner specified in the Privacy Agreement, please do not use the Site.",
            'is_published'=>1,
            'version_number'=>1,
            'created_at'=>Carbon::now(),
         
            'updated_at'=>Carbon::now(), 
        ]);

         
        DB::table('pages')->insert([
            'title'=>'Terms and Conditions',
            'slug'=>Str::slug('Terms and Conditions'),
            'thumbnail'=>'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'short_description'=> 'privacypolicy',
            'content'=>"1.PRIVACY
            Please review our Privacy Agreement, which also governs your visit to the Site. The personal information / data provided to us by you or your use of the Site will be treated as strictly confidential, in accordance with the Privacy Agreement and applicable laws and regulations. If you object to your
            information being transferred or used in the manner specified in the Privacy Agreement, please do not use the Site.",
            'is_published'=>1,
            'version_number'=>1,
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);

        DB::table('pages')->insert([
            'title'=>'Refund Policy',
            'slug'=>Str::slug('Refund Policy'),
            'thumbnail'=>'storage/images/category/root_category/1657964455lubo-minar-jxCFoEter1M-unsplash.jpg',
            'short_description'=> 'privacypolicy',
            'content'=>"1.PRIVACY
            Please review our Privacy Agreement, which also governs your visit to the Site. The personal information / data provided to us by you or your use of the Site will be treated as strictly confidential, in accordance with the Privacy Agreement and applicable laws and regulations. If you object to your
            information being transferred or used in the manner specified in the Privacy Agreement, please do not use the Site.",
            'is_published'=>1,
            'version_number'=>1,
            'created_at'=>Carbon::now(), 
            'updated_at'=>Carbon::now(), 
        ]);
    }
}
