<?php

namespace Database\Factories;

use App\Models\OttContent;
use App\Models\User;
use App\Models\UserActivitySync;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserActivitySync>
 */
class UserActivitySyncFactory extends Factory
{
    protected $model = UserActivitySync::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::whereHas('userDevice')->inRandomOrder()->limit(1)->first();
        $device = $user->userDevice()->inRandomOrder()->first();

        return [
            'content_id' => OttContent::all()->random()->id,
            'device_id' => $device->id,
            'user_id' => $user->id,
            'last_watch_time' => rand(3, 300),
        ];
    }
}
