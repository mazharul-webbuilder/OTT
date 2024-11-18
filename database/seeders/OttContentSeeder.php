<?php

namespace Database\Seeders;

use App\Enums\OttContentEnum;
use App\Models\OttContent;
use App\Models\SubSubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OttContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        // OttContent::factory()->count(10000)->create();
        $access = ['Free', 'Premium'];

        for ($i = 1; $i <= 52; $i++) {
            $sub_sub_category_id = SubSubCategory::where('id', $i)->first();
            if ($sub_sub_category_id->sub_category_id == 9) {
                for($b=1;$b<=5;$b++){
                    for ($a = 1; $a <= 5; $a++) {
                        DB::table('ott_contents')->insert([
                            'uuid' => $faker->unique()->uuid(),
                            'title' => $faker->realText($faker->numberBetween(10, 20)) . "Episode-" . $a,
                            'short_title' => $faker->sentence(),
                            'root_category_id' => $sub_sub_category_id->root_category_id,
                            'sub_category_id' => $sub_sub_category_id->sub_category_id,
                            'sub_sub_category_id' => $sub_sub_category_id->id,
                            'series_id' => $b,
                            'content_type_id' => $faker->numberBetween(1, 2),
                            'description' => $faker->paragraph(),
                            'year' => '2022',
                            'runtime' => $faker->numberBetween(30, 100),
                            'order' => $a,
                            // 'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
                            // 'cloud_url'=>'cricket',
                            'view_count' => '1',
                            'release_date' => Carbon::today()->subDays(rand(0, 365)),
                            'status' => 'Published',
                            'access' => $access[$faker->numberBetween(0, 1)],
                            'poster' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                            'backdrop' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }    
            }
            else if ($sub_sub_category_id->sub_category_id == 8) {
                for($b=1;$b<=5;$b++){
                    for ($a = 1; $a <= 5; $a++) {
                        DB::table('ott_contents')->insert([
                            'uuid' => $faker->unique()->uuid(),
                            'title' => $faker->realText($faker->numberBetween(10, 20)) . "Episode-" . $a,
                            'short_title' => $faker->sentence(),
                            'root_category_id' => $sub_sub_category_id->root_category_id,
                            'sub_category_id' => $sub_sub_category_id->sub_category_id,
                            'sub_sub_category_id' => $sub_sub_category_id->id,
                            'series_id' => $b,
                            'content_type_id' => $faker->numberBetween(1, 2),
                            'description' => $faker->paragraph(),
                            'year' => '2022',
                            'runtime' => $faker->numberBetween(30, 100),
                            'order' => $a,
                            // 'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
                            // 'cloud_url'=>'cricket',
                            'view_count' => '1',
                            'release_date' => Carbon::today()->subDays(rand(0, 365)),
                            'status' => 'Published',
                            'access' => $access[$faker->numberBetween(0, 1)],
                            'poster' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                            'backdrop' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }    
            }
            
            else{
                for ($j = 0; $j < 15; $j++) {
                    DB::table('ott_contents')->insert([
                        'uuid' => $faker->unique()->uuid(),
                        'title' => $faker->realText($faker->numberBetween(10, 20)),
                        'short_title' => $faker->sentence(),
                        'root_category_id' => $sub_sub_category_id->root_category_id,
                        'sub_category_id' => $sub_sub_category_id->sub_category_id,
                        'sub_sub_category_id' => $sub_sub_category_id->id,
                        'content_type_id' => $faker->numberBetween(1, 2),
                        'description' => $faker->paragraph(),
                        'year' => '2022',
                        'runtime' => $faker->numberBetween(30, 100),
                        // 'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
                        // 'cloud_url'=>'cricket',
                        'view_count' => '1',
                        'release_date' => Carbon::today()->subDays(rand(0, 365)),
                        'status' => 'Published',
                        'access' => $access[$faker->numberBetween(0, 1)],
                        'poster' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                        'backdrop' => "https://static.durbar.live/ott/images/content/content-" . $faker->numberBetween(1, 8) . ".jpg",
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
            
        }
    }
}
