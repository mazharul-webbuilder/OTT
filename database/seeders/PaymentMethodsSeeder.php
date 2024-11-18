<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            'name' => 'Bkash',
            'slug' => Str::slug('Bkash'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('payment_methods')->insert([
            'name' => 'Nagad',
            'slug' => Str::slug('Nagad'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('payment_methods')->insert([
            'name' => 'Aamarpay',
            'slug' => Str::slug('Aamarpay'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
        DB::table('payment_methods')->insert([
            'name' => 'Upay',
            'slug' => Str::slug('Upay'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
