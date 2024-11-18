<?php

namespace Database\Seeders;

use App\Models\CouponCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CouponCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        CouponCode::insert(
            [
                [
                    'code' => 'fest21',
                    'start_date' => Carbon::now(),
                    'expiry_date' => Carbon::now()->addSeconds(500000),
                    'discount_type' => 'flat',
                    'discount_value' => 40,
                    'maximum_amount_for_percent' => 40,
                    'created_at' => Carbon::now(),
                ],
                [
                    'code' => 'fest22',
                    'start_date' => Carbon::now(),
                    'expiry_date' => Carbon::now()->addSeconds(500000),
                    'discount_type' => 'flat',
                    'discount_value' => 50,
                    'maximum_amount_for_percent' => 50,
                    'created_at' => Carbon::now(),
                ],
                [
                    'code' => 'fest23',
                    'start_date' => Carbon::now(),
                    'expiry_date' => Carbon::now()->addSeconds(500000),
                    'discount_type' => 'flat',
                    'discount_value' => 10,
                    'maximum_amount_for_percent' => 10,
                    'created_at' => Carbon::now(),
                ],
                [
                    'code' => 'fest24',
                    'start_date' => Carbon::now(),
                    'expiry_date' => Carbon::now()->addSeconds(500000),
                    'discount_type' => 'flat',
                    'discount_value' => 80,
                    'maximum_amount_for_percent' => 80,
                    'created_at' => Carbon::now(),
                ],
            ]
        );
    }
}
