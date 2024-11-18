<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionSourceFormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sources = ['144p','240p','360p','720p','1080p','1440p'];
        return [
            'subscription_plan_id' => $this->faker->numberBetween(1, 4),  
            'source_format' => $sources[rand(0,5)], 
            'created_at' => Carbon::now(),
        ];
    }
}
