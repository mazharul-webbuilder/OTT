<?php

namespace Database\Seeders;

use App\Models\SubscriptionSourceFormat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSourceFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionSourceFormat::factory()->count(50)->create();
    }
}
