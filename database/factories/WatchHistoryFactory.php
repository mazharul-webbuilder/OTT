<?php

namespace Database\Factories;

use App\Models\OttContent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WatchHistory>
 */
class WatchHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ottContentIds = OttContent::pluck('id')->take(50)->toArray();
        return [
            'ott_content_id' => Arr::random($ottContentIds),
            'ott_content_type' => Arr::random(['single', 'series']),
            'watched_duration' => $this->faker->randomDigitNotZero(),
        ];
    }
}
