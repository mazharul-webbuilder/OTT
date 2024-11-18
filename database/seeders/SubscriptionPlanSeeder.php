<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscription_plans')->insert([
            'plan_name' => 'Half Yearly Plan',
            'plan_slug' => Str::slug('Half Yearly Plan'),
            'description' => "Enjoy Premium Now!
                            Unlimited Streaming
                            Validity 180 Days (*auto renew)
                            1 Screen at a Time
                            TV Browser Supported
                            Live and Exclusive English Premier League
                            All Bangladesh Home Series",
            'regular_price' => 499,
            'duration' => 180,
            'status' => 'Active',
            'number_of_allowed_stream' => 1,
            'number_of_allowed_device' => 5,
            'is_renewable'=>1,
        ]);
 
        DB::table('subscription_plans')->insert([
            'plan_name' => 'Monthly Pack',
            'plan_slug' => Str::slug('Monthly Pack'),
            'description' => "Enjoy Premium Now!
                                Unlimited Streaming
                                Validity 30 Days (*auto renew)
                                1 Screen at a Time
                                TV Browser Supported
                                Live and Exclusive English Premier League
                                All Bangladesh Home Series",
            'duration' => 30,
            'status' => 'Active',
            'is_renewable'=>1,
            'is_discounted'=>1,
            'regular_price' => 99,
            'discount_rate'=>20,
            'discount_type'=>'flat',
            'discount_price'=>79,
            'number_of_allowed_stream' => 1,
            'number_of_allowed_device' => 3,
        ]);
        
        DB::table('subscription_plans')->insert([
            'plan_name' => 'Daily Pack',
            'plan_slug' => Str::slug('Daily Pack'),
            'description' => "Enjoy Premium Now!
                                Validity 24 hours
                                Unlimited Streaming
                                1 Screen at a Time
                                TV-Browser Supported
                                Live & Exclusive English Premier League
                                All Bangladesh Home Series",
            'regular_price' => 20,
            'duration' => 1,
            'status' => 'Active',
            'number_of_allowed_stream' => 1,
            'number_of_allowed_device' => 1,
        ]);

        DB::table('subscription_plans')->insert([
            'plan_name' => 'Yearly Plan',
            'plan_slug' => Str::slug('Yearly Plan'),
            'description' => "Enjoy Premium Now!
                            Unlimited Streaming
                            Validity 365 Days (*auto renew)
                            1 Screen at a Time
                            TV Browser Supported
                            Live and Exclusive English Premier League
                            All Bangladesh Home Series",
            'regular_price' => 1000,
            'duration' => 365,
            'status' => 'Active',
            'is_renewable'=>1,
            'number_of_allowed_stream' => 2,
            'number_of_allowed_device' => 6,
        ]);
         
    } 
}
