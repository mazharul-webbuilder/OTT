<?php

namespace Database\Factories;

use App\Enums\OttContentEnum;
use App\Models\OttContent;
use App\Models\RootCategory;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OttContentFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $access = ['Free', 'Premium'];
        $root_category = $this->faker->numberBetween(1, 4);
        $sub_cat_id = SubCategory::where('root_category_id', $root_category)->select('id')->first();

        return [
            'uuid' => $this->faker->unique()->uuid(),
            'title' => $this->faker->realText($this->faker->numberBetween(10, 20)),
            'short_title' => $this->faker->sentence(),
            'root_category_id' => $root_category,
            'sub_category_id' => $sub_cat_id,
            'content_type_id' => $this->faker->numberBetween(1, 2),
            'description' => $this->faker->paragraph(),
            'year' => '2022',
            'runtime' => $this->faker->numberBetween(30, 100),
            // 'youtube_url' => 'https://www.youtube.com/watch?v=Gmh_ztxH-uo',
            // 'cloud_url'=>'cricket',
            'view_count' => '1',
            'release_date' => Carbon::today()->subDays(rand(0, 365)),
            'status' => 'Published',
            'access' => $access[$this->faker->numberBetween(0, 1)],
            'poster' => "https://static.durbar.live/ott/images/content/content-" . $this->faker->numberBetween(1, 8) . ".jpg",
            'backdrop' => "https://static.durbar.live/ott/images/content/content-" . $this->faker->numberBetween(1, 8) . ".jpg",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
