<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'account_status' => $this->faker->randomElement(['Pending', 'Blocked', 'Active']),
            'is_verified' => $this->faker->boolean,
            'phone' => "01".rand(3,7)."32".rand(3,7)."7".rand(0,9)."2".rand(10,99),
            'otp' => $this->faker->unique()->numberBetween(100000,999999),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
