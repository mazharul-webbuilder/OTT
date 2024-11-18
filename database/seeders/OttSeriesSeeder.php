<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OttSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ott_series')->insert([
            [
                'title' => 'সিন্ডিকেট',
                'slug' => Str::slug('সিন্ডিকেট'),
                'root_category_id' => 3,
                'sub_category_id' => 8,
                'status' => fake()->randomElement(['Pending', 'Hold', 'Published']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'ষ',
                'slug' => Str::slug('ষ'),
                'root_category_id' => 3,
                'sub_category_id' => 8,
                'status' => fake()->randomElement(['Pending', 'Hold', 'Published']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'শাটিকাপ',
                'slug' => Str::slug('শাটিকাপ'),
                'root_category_id' => 3,
                'sub_category_id' => 8,
                'status' => fake()->randomElement(['Pending', 'Hold', 'Published']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'ফোর টোয়েন্টি',
                'slug' => Str::slug('ফোর টোয়েন্টি'),
                'root_category_id' => 3,
                'sub_category_id'=>9,
                'status' => fake()->randomElement(['Pending', 'Hold', 'Published']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => '1st Test',
                'slug' => Str::slug('1st Test South Africa vs Bangladesh 2022'),
                'root_category_id' => 1,
                'sub_category_id' => 1,
                'status' => fake()->randomElement(['Pending', 'Hold', 'Published']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
