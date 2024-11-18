<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use App\Models\OttContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketplaces = [
            'Android',
            'LG',
            'MI'
        ];

        $contentIds = OttContent::pluck('id')->take(3);

        foreach ($marketplaces as $marketplace) {
            $marketplace = Marketplace::create([
                'name' => $marketplace,
                'slug' => Str::slug($marketplace)
            ]);

            $marketplace->ottContents()->attach($contentIds);
        }
    }
}
