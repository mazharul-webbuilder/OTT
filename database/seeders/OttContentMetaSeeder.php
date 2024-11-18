<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OttContentMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ott_content_metas')->insert([
            'content_id'=>1, 
            'key'=>'genre', 
            'value'=>'Drama', 
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);


        DB::table('ott_content_metas')->insert([
            'content_id'=>1, 
            'key'=>'director', 
            'value'=>'শ্রাবনী ফেরদৌস', 
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        DB::table('ott_content_metas')->insert([
            'content_id'=>1, 
            'key'=>'casts', 
            'value'=>'আবির মির্জা, নীলাঞ্জনা নীলা, মিলি বাশার, তাসফিয়া আমিন, ইরফান হাবিব অয়ন, আকাশ, এম আর মাহি। ', 
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
