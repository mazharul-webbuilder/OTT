<?php

use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CastAndCrewController;
use App\Http\Controllers\Backend\ContentCreate\ContentStepController;
use App\Http\Controllers\Backend\ContentOwnerController;
use App\Http\Controllers\Backend\FrontendCustomContentController;
use App\Http\Controllers\Backend\FrontendCustomContentSectionController;
use App\Http\Controllers\Backend\FrontendCustomContentSectionSliderController;
use App\Http\Controllers\Backend\FrontendCustomSliderController;
use App\Http\Controllers\Backend\IpBlackListController;
use App\Http\Controllers\Backend\IpWhiteListController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\OttContentController;
use App\Http\Controllers\Backend\OttContentTypeController;
use App\Http\Controllers\Backend\OttSeasonController;
use App\Http\Controllers\Backend\OttSliderController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RootCategoryController;
use App\Http\Controllers\Backend\SelectedCategoryContentController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubscriptionPlanController;
use App\Http\Controllers\Backend\SubscriptionSourceFormatController;
use App\Http\Controllers\Backend\SubSubCategoryController;
use App\Http\Controllers\Backend\UserBlackListController;
use App\Http\Controllers\Backend\OttSeriesController;
use App\Http\Controllers\Backend\PhotoGalaryController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use Tapp\LaravelUppyS3MultipartUpload\Http\Controllers\UppyS3MultipartController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\MarketplaceController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\PublishedContentController;
use App\Http\Controllers\Backend\ActivityController;
use App\Http\Controllers\Backend\UpcomingContentController;
use App\Http\Controllers\Backend\ContentWatchHistoryController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\AdminPasswordResetController;
use App\Http\Controllers\Backend\CouponCodeController;
use App\Http\Controllers\Backend\TVodSubscriptionController;
use App\Http\Controllers\Backend\NewsLetterController;

Route::group(['prefix' => 'v1/admin'], function () {

    //    Route::options('/s3/multipart', [UppyS3MultipartControllerr::class, 'createPreflightHeader']);
    Route::post('/s3/multipart', [UppyS3MultipartController::class, 'createMultipartUpload']);
    Route::get('/s3/multipart/{uploadId}', [UppyS3MultipartController::class, 'getUploadedParts']);
    Route::post('/s3/multipart/{uploadId}/complete', [UppyS3MultipartController::class, 'completeMultipartUpload']);
    Route::delete('/s3/multipart/{uploadId}', [UppyS3MultipartController::class, 'abortMultipartUpload']);
    Route::get('/s3/multipart/{uploadId}/{partNumber}', [UppyS3MultipartController::class, 'signPartUpload']);


    //Encoding API
    Route::get('/encoding-pending-source', [OttContentController::class, 'encodingPendingSource'])->name('encoding.pending.source');
    Route::post('/encoding-status-update', [OttContentController::class, 'encodingStatusUpdate'])->name('encoding.status.update');
    Route::post('/encoding-single-source', [OttContentController::class, 'encodingSingleSource'])->name('encoding.single.source');

    // Admin Login
    Route::post('/login', [AdminAuthController::class, 'adminLogin']);
    // Admin Logout
    Route::middleware(['authorization'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('adminlogout');

        //notification
        Route::post('send-notification', [NotificationController::class, 'postNotification']);
        Route::get('get-notification', [NotificationController::class, 'getNotification']);
        Route::delete('delete-notification/{id}', [NotificationController::class, 'deleteNotification']);

        // Admin Profile
        Route::get('get-profile', [AdminProfileController::class, 'getAdminProfile']);
        Route::post('set-profile/{id}', [AdminProfileController::class, 'setAdminProfile']);
        Route::post('password-reset', [AdminProfileController::class, 'updatePassword']);

        // Route::group([

        //     'middleware' => CheckAdminAuthorization::class

        // ], function ($router) {
        //     Route::post('/logout', [AdminAuthController::class, 'logout'])->name('adminlogout');
        // });

        Route::resource('root-categories', RootCategoryController::class);
        Route::post('update-root-category', [RootCategoryController::class, 'update']);
        Route::get('root-categories-list', [RootCategoryController::class, 'rootCategoriesList']);
        Route::resource('sub-categories-admin', SubCategoryController::class);
        Route::post('update-sub-category', [SubCategoryController::class, 'update']);
        Route::get('sub-categories-list', [SubCategoryController::class, 'subCategoriesList']);
        Route::resource('sub-sub-categories-admin', SubSubCategoryController::class);
        Route::resource('ott-slider', OttSliderController::class);
        Route::resource('page', PageController::class);
        Route::resource('frontend-custom-slider', FrontendCustomSliderController::class);
        Route::resource('frontend-custom-contents', FrontendCustomContentController::class);
        Route::get('frontend-custom-contents-by-section-id/{FrontendSectionId}', [FrontendCustomContentController::class, 'contentsByFrontentSectionID']);
        Route::resource('frontend-custom-content-sections', FrontendCustomContentSectionController::class);
        Route::resource('frontend-custom-section-sliders', FrontendCustomContentSectionSliderController::class);
        Route::resource('ott-content-types', OttContentTypeController::class);
        Route::resource('selected-category-contents', SelectedCategoryContentController::class);
        Route::resource('subscription-source-formats', SubscriptionSourceFormatController::class);
        Route::resource('ott-contents', OttContentController::class)->except('index');
        Route::post('ott-content-upload', [OttContentController::class, 'uploadContentFile']);
        Route::get('ott-content-process-status/{content_id}', [OttContentController::class, 'contentProcessStatus']);
        Route::resource('subscription-plans', SubscriptionPlanController::class);
        Route::resource('admin-user', AdminController::class);
        Route::resource('role', RoleController::class);
        // Marketplace
        Route::resource('marketplace', MarketplaceController::class);
        Route::get('all-content-with-available-marketplaces', [MarketplaceController::class, 'getContentWithAvailableMarketplaces']);
        // Publishing contents
        Route::get('all-publishing-contents', [PublishedContentController::class, 'getAllPublishedContent']);
        // Upcoming Contents
        Route::get('upcoming-contents', [UpcomingContentController::class, 'getUpcomingContents']);
        // Get Permissions
        Route::get('permissions', [PermissionController::class, 'index']);

        Route::resource('user-blacklist', UserBlackListController::class);
        Route::resource('ip-blacklist', IpBlackListController::class);
        Route::resource('ip-whitelist', IpWhiteListController::class);

        //filter sub_category by root_category_id
        Route::get('filter-sub_category/{id}', [SubSubCategoryController::class, 'filterSubCategory']);
        Route::get('filter-sub_sub_category/{id}', [OttContentController::class, 'filterSubSubCategory']);
        Route::get('filter-sub_category-with-content/{id}', [SubSubCategoryController::class, 'filterSubCategoryWithContent']);
        Route::get('filter-sub_sub_category-with-content/{id}', [OttContentController::class, 'filterSubSubCategoryWithContent']);
        Route::get('filter-sub_sub_category-content/{id}', [OttContentController::class, 'filterSubSubCategoryContent']);

        //Elasticsearch
        Route::post('create-ott_content-index', [OttContentController::class, 'createOttcontentIndex']);
        Route::post('add-ott_contents-to-index', [OttContentController::class, 'addOttContentsToIndex']);
        Route::get('ott_content-delete-index', [OttContentController::class, 'ottContentDeleteIndex']);
        # AWS_USE_ACCELERATE_ENDPOINT=true

        //frontent-custo-content
        // Route::resource('frontend-custom-content-section', FrontendCustomContentSectionController::class);

        //content-owners
        Route::resource('content-owners', ContentOwnerController::class);
        Route::post('content-owners-update/{contentOwner}', [ContentOwnerController::class, 'update']);
        Route::get('content-owners-all', [ContentOwnerController::class, 'getAllOwners']);

        //content-upload-step-by-step

        //general step
        Route::post('store-general-step-data', [ContentStepController::class, 'storeGeneralStepData']);
        Route::post('update-general-step-data/{uuid}', [ContentStepController::class, 'updateGeneralStepData']);
        Route::get('get-general-step-data/{uuid}', [ContentStepController::class, 'getGeneralStepData']);

        //Cast and Crews step
        Route::post('update-cast-crew-step-data/{uuid}', [ContentStepController::class, 'updateCastCrewStepData']);
        Route::get('get-cast-crew-step-data/{uuid}', [ContentStepController::class, 'getCastCrewStepData']);


        //Graphics step
        Route::post('update-graphics-step-data/{uuid}', [ContentStepController::class, 'updateGraphicStepData']);
        Route::get('get-graphics-step-data/{uuid}', [ContentStepController::class, 'getGraphicStepData']);


        //Media step
        Route::post('update-media-step-data/{uuid}', [ContentStepController::class, 'updateMediaStepData']); //Media step

        //Marketplace step
        Route::get('get-content-marketplaces/{uuid}', [ContentStepController::class, 'getContentMarketplaces']);
        Route::put('update-content-marketplaces/{uuid}', [ContentStepController::class, 'updateContentMarketplaces']);

        //Legal & Distribution
        Route::get('countries', [ContentStepController::class, 'getAllCountries']);
        Route::get('get-content-restricted-countries/{uuid}', [ContentStepController::class, 'getContentRestrictedCountries']);
        Route::put('content-restricted-country-update/{uuid}', [ContentStepController::class, 'updateContentRestrictedCountries']);

        Route::get('get-content-downloadable-info/{uuid}', [ContentStepController::class, 'getContentDownloadableInfo']);
        Route::put('set-content-downloadable-info/{uuid}', [ContentStepController::class, 'setContentDownloadableInfo']);

        //Published Content
        Route::post('publish-content/{uuid}', [ContentStepController::class, 'publishContent']);

        //Get Contents
        Route::get('all-content', [ContentStepController::class, 'index']);

        //Get Streams
        Route::get('all-stream', [ContentStepController::class, 'getStreams']);



        //Player Events step
        Route::post('update-player-events-step-data/{uuid}', [ContentStepController::class, 'updatePlayerEventStepData']);
        Route::get('get-player-events-step-data/{uuid}', [ContentStepController::class, 'getPlayerEventStepData']);

        //Cast and Crew
        Route::resource('cast-and-crew', CastAndCrewController::class);
        Route::post('cast-and-crew-update', [CastAndCrewController::class, 'update']);
        Route::get('cast-and-crew-all', [CastAndCrewController::class, 'getCastAndCrew']);

        //Photo Galary
        Route::resource('photo-galary', PhotoGalaryController::class);
        Route::post('photo-galary-update', [PhotoGalaryController::class, 'update']);

        //Show Content for preview
        Route::get('show-content/{uuid}', [ContentStepController::class, 'showContent']);

        //SFTP Manual Upload
        Route::post('generate-file-names/{uuid}', [ContentStepController::class, 'generateFileNames']);

        //End content-upload-step-by-step


        //Series
        Route::resource('ott-series', OttSeriesController::class);
        Route::get('get-series', [OttSeriesController::class, 'getSeries']);

        //Season
        Route::resource('ott-season', OttSeasonController::class);
        Route::get('filter-season/{id}', [OttSeasonController::class, 'filterSeasons']);

        //Episode
        Route::get('get-episode-number/{id}', [OttSeasonController::class, 'getEpisodeNumber']);

        Route::get('get-episodes', [OttSeasonController::class, 'getEpisodes']);


        //customer-list
        Route::get('customer-list', [UserController::class, 'getCustomerList']);
        Route::post('customer-delete/{id}', [UserController::class, 'deleteCustomer']);
        Route::post('customer-change-status/{id}', [UserController::class, 'changeCustomerStatus']);
        /**
         * Settings Area
         */
        //platform setting
        Route::get('get-settings', [SettingsController::class, 'getSettings']);
        Route::post('set-settings', [SettingsController::class, 'setSetting']);

        //Activity History
        Route::get('activities/all', [ActivityController::class, 'getAllActivities']);
        Route::delete('activity/delete/{id}', [ActivityController::class, 'activityDelete']);
        Route::post('activity/multiple-delete', [ActivityController::class, 'multipleDelete']);
        // Watch Histories
        Route::get('content-watch/histories', [ContentWatchHistoryController::class, 'getAllWatchHistory']);
        // Report
        Route::get('subscription-report', [ReportController::class, 'getSubscriptionReport']);
        Route::get('user-report', [ReportController::class, 'getUserReport']);
    });


    // Password Reset
    Route::post('sent-verification-code', [AdminPasswordResetController::class, 'sendVerificationCode']);
    Route::post('verify-password-reset-code', [AdminPasswordResetController::class, 'verifyCode']);
    Route::post('set-new-password', [AdminPasswordResetController::class, 'setNewPassword']);


    // Admin Profile
    Route::get('get-profile', [AdminProfileController::class, 'getAdminProfile']);
    Route::post('set-profile/{id}', [AdminProfileController::class, 'setAdminProfile']);
    Route::post('password-reset', [AdminProfileController::class, 'updatePassword']);

    // Route::group([

    //     'middleware' => CheckAdminAuthorization::class

    // ], function ($router) {
    //     Route::post('/logout', [AdminAuthController::class, 'logout'])->name('adminlogout');
    // });

    Route::resource('root-categories', RootCategoryController::class);
    Route::post('update-root-category', [RootCategoryController::class, 'update']);
    Route::get('root-categories-list', [RootCategoryController::class, 'rootCategoriesList']);
    Route::resource('sub-categories-admin', SubCategoryController::class);
    Route::post('update-sub-category', [SubCategoryController::class, 'update']);
    Route::get('sub-categories-list', [SubCategoryController::class, 'subCategoriesList']);
    Route::resource('sub-sub-categories-admin', SubSubCategoryController::class);
    Route::resource('ott-slider', OttSliderController::class);
    Route::resource('page', PageController::class);
    Route::resource('frontend-custom-slider', FrontendCustomSliderController::class);
    Route::resource('frontend-custom-contents', FrontendCustomContentController::class);
    Route::get('frontend-custom-contents-by-section-id/{FrontendSectionId}', [FrontendCustomContentController::class, 'contentsByFrontentSectionID']);
    Route::resource('frontend-custom-content-sections', FrontendCustomContentSectionController::class);
    Route::resource('frontend-custom-section-sliders', FrontendCustomContentSectionSliderController::class);
    Route::resource('ott-content-types', OttContentTypeController::class);
    Route::resource('selected-category-contents', SelectedCategoryContentController::class);
    Route::resource('subscription-source-formats', SubscriptionSourceFormatController::class);
    Route::resource('ott-contents', OttContentController::class)->except('index');
    Route::post('ott-content-upload', [OttContentController::class, 'uploadContentFile']);
    Route::get('ott-content-process-status/{content_id}', [OttContentController::class, 'contentProcessStatus']);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('admin-user', AdminController::class);
    Route::resource('role', RoleController::class);
    // Marketplace
    Route::resource('marketplace', MarketplaceController::class);
    Route::get('all-content-with-available-marketplaces', [MarketplaceController::class, 'getContentWithAvailableMarketplaces']);
    // Publishing contents
    Route::get('all-publishing-contents', [PublishedContentController::class, 'getAllPublishedContent']);
    // Upcoming Contents
    Route::get('upcoming-contents', [UpcomingContentController::class, 'getUpcomingContents']);
    // Get Permissions
    Route::get('permissions', [PermissionController::class, 'index']);

    Route::resource('user-blacklist', UserBlackListController::class);
    Route::resource('ip-blacklist', IpBlackListController::class);
    Route::resource('ip-whitelist', IpWhiteListController::class);

    //filter sub_category by root_category_id
    Route::get('filter-sub_category/{id}', [SubSubCategoryController::class, 'filterSubCategory']);
    Route::get('filter-sub_sub_category/{id}', [OttContentController::class, 'filterSubSubCategory']);
    Route::get('filter-sub_category-with-content/{id}', [SubSubCategoryController::class, 'filterSubCategoryWithContent']);
    Route::get('filter-sub_sub_category-with-content/{id}', [OttContentController::class, 'filterSubSubCategoryWithContent']);
    Route::get('filter-sub_sub_category-content/{id}', [OttContentController::class, 'filterSubSubCategoryContent']);

    //Elasticsearch
    Route::post('create-ott_content-index', [OttContentController::class, 'createOttcontentIndex']);
    Route::post('add-ott_contents-to-index', [OttContentController::class, 'addOttContentsToIndex']);
    Route::get('ott_content-delete-index', [OttContentController::class, 'ottContentDeleteIndex']);
    # AWS_USE_ACCELERATE_ENDPOINT=true

    //frontent-custo-content
    // Route::resource('frontend-custom-content-section', FrontendCustomContentSectionController::class);

    //content-owners
    Route::resource('content-owners', ContentOwnerController::class);
    Route::post('content-owners-update/{contentOwner}', [ContentOwnerController::class, 'update']);
    Route::get('content-owners-all', [ContentOwnerController::class, 'getAllOwners']);

    //content-upload-step-by-step

    //general step
    Route::post('store-general-step-data', [ContentStepController::class, 'storeGeneralStepData'])->middleware('authorization');
    Route::post('update-general-step-data/{uuid}', [ContentStepController::class, 'updateGeneralStepData'])->middleware('authorization');
    Route::get('get-general-step-data/{uuid}', [ContentStepController::class, 'getGeneralStepData']);

    //Cast and Crews step
    Route::post('update-cast-crew-step-data/{uuid}', [ContentStepController::class, 'updateCastCrewStepData']);
    Route::get('get-cast-crew-step-data/{uuid}', [ContentStepController::class, 'getCastCrewStepData']);


    //Graphics step
    Route::post('update-graphics-step-data/{uuid}', [ContentStepController::class, 'updateGraphicStepData']);
    Route::get('get-graphics-step-data/{uuid}', [ContentStepController::class, 'getGraphicStepData']);


    //Media step
    Route::post('update-media-step-data/{uuid}', [ContentStepController::class, 'updateMediaStepData']); //Media step

    //Marketplace step
    Route::get('get-content-marketplaces/{uuid}', [ContentStepController::class, 'getContentMarketplaces']);
    Route::put('update-content-marketplaces/{uuid}', [ContentStepController::class, 'updateContentMarketplaces']);

    //Legal & Distribution
    Route::get('countries', [ContentStepController::class, 'getAllCountries']);
    Route::get('get-content-restricted-countries/{uuid}', [ContentStepController::class, 'getContentRestrictedCountries']);
    Route::put('content-restricted-country-update/{uuid}', [ContentStepController::class, 'updateContentRestrictedCountries']);

    Route::get('get-content-downloadable-info/{uuid}', [ContentStepController::class, 'getContentDownloadableInfo']);
    Route::put('set-content-downloadable-info/{uuid}', [ContentStepController::class, 'setContentDownloadableInfo']);

    //Published Content
    Route::post('publish-content/{uuid}', [ContentStepController::class, 'publishContent']);

    //Get Contents
    Route::get('all-content', [ContentStepController::class, 'index']);

    //Get Streams
    Route::get('all-stream', [ContentStepController::class, 'getStreams']);



    //Player Events step
    Route::post('update-player-events-step-data/{uuid}', [ContentStepController::class, 'updatePlayerEventStepData']);
    Route::get('get-player-events-step-data/{uuid}', [ContentStepController::class, 'getPlayerEventStepData']);

    //Cast and Crew
    Route::resource('cast-and-crew', CastAndCrewController::class);
    Route::post('cast-and-crew-update', [CastAndCrewController::class, 'update']);
    Route::get('cast-and-crew-all', [CastAndCrewController::class, 'getCastAndCrew']);

    //Photo Galary
    Route::resource('photo-galary', PhotoGalaryController::class);
    Route::post('photo-galary-update', [PhotoGalaryController::class, 'update']);

    //Show Content for preview
    Route::get('show-content/{uuid}', [ContentStepController::class, 'showContent']);

    //SFTP Manual Upload
    Route::post('generate-file-names/{uuid}', [ContentStepController::class, 'generateFileNames']);

    //End content-upload-step-by-step


    //Series
    Route::resource('ott-series', OttSeriesController::class);
    Route::get('get-series', [OttSeriesController::class, 'getSeries']);

    //Season
    Route::resource('ott-season', OttSeasonController::class);
    Route::get('filter-season/{id}', [OttSeasonController::class, 'filterSeasons']);

    //Episode
    Route::get('get-episode-number/{id}', [OttSeasonController::class, 'getEpisodeNumber']);

    Route::get('get-episodes', [OttSeasonController::class, 'getEpisodes']);


    //customer-list
    Route::get('customer-list', [UserController::class, 'getCustomerList']);
    Route::post('customer-delete/{id}', [UserController::class, 'deleteCustomer']);
    Route::post('customer-change-status/{id}', [UserController::class, 'changeCustomerStatus']);

    //platform setting
    Route::get('get-settings', [SettingsController::class, 'getSettings']);
    Route::post('set-settings', [SettingsController::class, 'setSetting']);

    //Activity History
    Route::get('activities/all', [ActivityController::class, 'getAllActivities']);
    Route::delete('activity/delete/{id}', [ActivityController::class, 'activityDelete']);
    Route::post('activity/multiple-delete', [ActivityController::class, 'multipleDelete']);
    // Report
    Route::get('subscription-report', [ReportController::class, 'getSubscriptionReport']);
    Route::get('user-report', [ReportController::class, 'getUserReport']);
    Route::get('reports/subscription-payment', [ReportController::class, 'getSubscriptionNdPaymentReport']);
    Route::get('reports/trending-contents', [ReportController::class, 'getTrendingContentReport']);
    Route::get('reports/content-watch-history', [ReportController::class, 'getContentWatchHistoryReport']);
    // Coupon-code
    Route::resource('coupon-codes', CouponCodeController::class);
    Route::get('get-coupon-code-subscription-plans', [CouponCodeController::class, 'getCouponCodeSubscriptionsPlans']);
    Route::get('show-code-subscription-plans/{id}', [CouponCodeController::class, 'showCouponCodeSubscriptionsPlans']);
    Route::post('coupon-code-subscription-plans', [CouponCodeController::class, 'storeSubscriptionPlan']);
    Route::put('update-coupon-code-subscription-plans/{id}', [CouponCodeController::class, 'updateSubscriptionPlan']);
    Route::delete('delete-coupon-code-subscription-plans/{id}', [CouponCodeController::class, 'destroySubscriptionPlan']);

    //TVod-Subscriptions
    Route::apiResource('t_vod_subscriptions', TVodSubscriptionController::class);
    Route::get('get-lite-ott-content-list',[OttContentController::class, 'getLiteOttContentList']);


    // Newsletter
    Route::get('newsletter/history', [NewsLetterController::class, 'index']);
    Route::post('newsletter/send', [NewsLetterController::class, 'newsletterSend']);
    Route::delete('newsletter/delete/{id}', [NewsLetterController::class, 'deleteNewsletter']);
});
