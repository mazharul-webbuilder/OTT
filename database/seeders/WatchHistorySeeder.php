<?php

namespace Database\Seeders;

use App\Models\WatchHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WatchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WatchHistory::factory()->count(20)->create();
    }
}
