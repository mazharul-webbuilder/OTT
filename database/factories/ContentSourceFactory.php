<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $sources = ['720p'];
        return [
            'ott_content_id' => $this->faker->numberBetween(1, 780),
            'content_source' => "http://159.223.86.243/storage/videos/Vedio_" . $this->faker->numberBetween(1, 8) . ".mp4",
            'fps' => "20",
            'source_format' => '720p',
            'created_at' => Carbon::now(),
        ];
    }
}
