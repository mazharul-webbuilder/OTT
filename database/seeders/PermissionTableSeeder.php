<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        $permissions = [
            //for admin web panel
            //root-category
            'root-category-list',
            'root-category-create',
            'root-category-edit',
            'root-category-delete',
            //sub-category
            'sub-category-list',
            'sub-category-create',
            'sub-category-edit',
            'sub-category-delete',
            //sub-sub-category
            'sub-sub-category-list',
            'sub-sub-category-create',
            'sub-sub-category-edit',
            'sub-sub-category-delete',
            //ott-slider
            'ott-slider-list',
            'ott-slider-create',
            'ott-slider-edit',
            'ott-slider-delete',
            //ott-content type
            'ott-content-type-list',
            'ott-content-type-create',
            'ott-content-type-edit',
            'ott-content-type-delete',
            //ott-content
            'ott-content-list',
            'ott-content-create',
            'ott-content-edit',
            'ott-content-delete',
            'ott-content-upload-media-page',
            'ott-content-upload-media',
            'ott-media-delete',

            //ott-series
            'ott-series-list',
            'ott-series-create',
            'ott-series-edit',
            'ott-series-delete',


            //frontend-custom-content-section
            'frontend-custom-content-section-list',
            'frontend-custom-content-section-create',
            'frontend-custom-content-section-edit',
            'frontend-custom-content-section-delete',
            'frontend-custom-content-section-add-content',
            'edit-frontend-custom-content-section-contents',
            'frontend-custom-content-section-content-delete',
            // admin user
            'admin-user-list',
            'admin-user-create',
            'admin-user-edit',
            'admin-user-delete',
            // role
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // cast & crew
            'cast-crew-list',
            'cast-crew-create',
            'cast-crew-edit',
            'cast-crew-delete',
            // content owner
            'content-owner-list',
            'content-owner-create',
            'content-owner-edit',
            'content-owner-delete',
            // ip black list
            'ip-blacklist-list',
            'ip-blacklist-create',
            'ip-blacklist-edit',
            'ip-blacklist-delete',
            // ip white list
            'ip-whitelist-list',
            'ip-whitelist-create',
            'ip-whitelist-edit',
            'ip-whitelist-delete',
            // photo gallery
            'photo-gallery-list',
            'photo-gallery-create',
            'photo-gallery-edit',
            'photo-gallery-delete',
            // subscription plan
            'subscription-plan-list',
            'subscription-plan-create',
            'subscription-plan-edit',
            'subscription-plan-delete',
            // user black list
            'user-black-list-list',
            'user-black-list-create',
            'user-black-list-edit',
            'user-black-list-delete',
            // marketplace
            'marketplace-list',
            'marketplace-create',
            'marketplace-edit',
            'marketplace-delete',
            // Admin Profile update permission
            'admin-profile-get',
            'admin-profile-set',
            // all published content
            'all-published-content',
            // Activity Log
            'all-activity-log',
            'activity-delete',
            // upcoming contents
            'upcoming-contents',

            //Customer Report
            'customer-list',
            'customer-delete',

            //Notification
            'notification-list',
            'notification-create',
            'notification-delete',

            //Custom page
            'custom-page-list',
            'custom-page-edit',
            'custom-page-delete',
            //Newsletter
            'newsletter-history',
            'newsletter-create',
            'newsletter-delete',


        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission, 'guard_name' => 'admin_api']);
        // }
    }
}
