<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        return [
            'subscription_plan_id' => SubscriptionPlan::inRandomOrder()->value('id'),
            'user_id' => $this->faker->unique()->randomElement($userIds),
            'price' => rand(20, 1000),
        ];
    }
}
