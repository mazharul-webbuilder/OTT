<?php

namespace Database\Seeders;

use App\Models\FrontendCustomContentSectionSlider;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RootCategorySeeder::class,
            SubCategorySeeder::class,
            SubSubCategorySeeder::class,
            OttContentTypeSeeder::class,
            OttSeriesSeeder::class,
            OttContentSeeder::class,
            OttContentMetaSeeder::class,
            // OttSliderSeeder::class,
            FrontendCustomContentSectionSeeder::class,
            FrontendCustomContentSeeder::class,
            FrontendCustomSliderSeeder::class,
            SubscriptionPlanSeeder::class,
            CouponCodeSeeder::class,
            CouponCodeSubscriptionPlanSeeder::class,
            OttSeriesContentSeeder::class, //not included
            OttContentDataSeeder::class, //not included
            // ContentSourceSeeder::class,
            // UserMetaSeeder::class, //not included
            FrontendCustomContentSectionSliderSeeder::class,
            SubscriptionSourceFormatSeeder::class,
            PaymentMethodsSeeder::class,
            SelectedCategoryContentSeeder::class,
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            // CreateAdminApiUserSeeder::class
            // CreateContentManagerSeeder::class
            MarketplaceSeeder::class,
            WatchHistorySeeder::class,
//            CountrySeeder::class,
            UserSubscriptionSeeder::class,
        ]);
    }
}
