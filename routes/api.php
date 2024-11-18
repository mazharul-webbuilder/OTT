<?php

use App\Http\Controllers\Frontend\CustomContentSectionController;
use App\Http\Controllers\Frontend\CustomSliderController;
use App\Http\Controllers\Frontend\EncodeController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\OttContentReviewController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\PhotoGalleryController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SeasonController;
use App\Http\Controllers\Frontend\SeriesController;
use App\Http\Controllers\Frontend\SliderController;
use App\Http\Controllers\Frontend\SubscriptionController;
use App\Http\Controllers\Frontend\UserActivitySyncController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\UserLoginController;
use App\Http\Controllers\Frontend\WatchListController;
use App\Http\Middleware\CheckAuthorization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\OttContentController;
use App\Http\Controllers\Frontend\PageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
*title: frontend api
*description: here al the frontend data api exists
*Author: aman
*date:2022-07-14 16:39:22
*
*/

// Route::get('/get-root-categories',)


// backend-apis
// include('./routes/backend_api.php');

use Tapp\LaravelUppyS3MultipartUpload\Http\Controllers\UppyS3MultipartController;


Route::group(['prefix' => 'v1'], function () {

    Route::get('/', function () {
        return response()->json([
            "message" => "Application is running",
            "date_time" => Carbon::now('Asia/Dhaka'),
            "status" => "Active"
        ]);
    });

    Route::options('/s3/multipart', [UppyS3MultipartController::class, 'createPreflightHeader']);
    Route::post('/s3/multipart', [UppyS3MultipartController::class, 'createMultipartUpload']);
    Route::get('/s3/multipart/{uploadId}', [UppyS3MultipartController::class, 'getUploadedParts']);
    Route::post('/s3/multipart/{uploadId}/complete', [UppyS3MultipartController::class, 'completeMultipartUpload']);
    Route::delete('/s3/multipart/{uploadId}', [UppyS3MultipartController::class, 'abortMultipartUpload']);
    Route::get('/s3/multipart/{uploadId}/{partNumber}', [UppyS3MultipartController::class, 'signPartUpload']);


    /**
     * User Login Registration
     */
    // By Phone
    Route::post('{device}/phone-register', [UserLoginController::class, 'phoneRegister'])->name('login');
    Route::post('{device}/otp-verify', [UserLoginController::class, 'otpVerifyForLogin']);
    // By Google
    Route::post('{device}/user-login-with-google', [UserLoginController::class, 'loginWithGoogle']);
    // By Facebook
    Route::post('{device}/user-login-with-facebook', [UserLoginController::class, 'loginWithFacebook']);
    // By Email
    Route::post('{device}/login-with-email-verification-code', [UserLoginController::class, 'sendVerificationCodeForEmailLogin']);
    Route::post('{device}/verify-email-verification-code', [UserLoginController::class, 'verifyEmailVerificationCode']);
    Route::post('{device}/register-email-verified-user', [UserLoginController::class, 'registerEmailVerifiedUser']);
    Route::post('{device}/login-with-email-request', [UserLoginController::class, 'loginWithEmail']);

    /*====================================================================================================
    * Auth Routes
    * These routes are accessible only to authenticated users.
    ====================================================================================================*/
    Route::group(['middleware' => CheckAuthorization::class], function ($router) {
        // Auth
        Route::post('{device}/logout', [UserLoginController::class, 'logout'])->name('logout');
        Route::post('{device}/refresh', [UserLoginController::class, 'refresh']);
        // User
        Route::get('{device}/user-profile', [UserController::class, 'userProfile']);
        Route::put('{device}/user-profile-update', [UserController::class, 'updateProfile']);
        Route::put('{device}/user-avatar-update', [UserController::class, 'updateAvatar']);
        Route::get('{device}/auth/user/meta-info', [UserController::class, 'userMetaInfo']);
        Route::get('{device}/remove-user-image', [UserLoginController::class, 'removeProfileimage']);
        Route::post('{device}/user-meta', [UserLoginController::class, 'userMeta']);
        //Device Manage
        Route::post('{device}/device-logout', [FrontendController::class, 'userDeviceRemove']);
        Route::get('{device}/user/devices', [FrontendController::class, 'devices']);
        // Update user-activity-sync-for-premium-content
        Route::post('{device}/update-user-activity-sync', [UserActivitySyncController::class, 'updateUserSyncActivity']);
        // User Watch history track
        Route::post('{device}/track-watch-history', [FrontendController::class, 'trackUserWatchHistory']);
        //User Watchlist
        Route::get('{device}/watch-list', [WatchListController::class, 'watchList']);
        Route::get('{device}/remove-watch-list/{id}', [WatchListController::class, 'watchListRemove']);
        Route::delete('{device}/remove-all-watch-list', [WatchListController::class, 'watchListAllRemove']);
        // User Wishlist
        Route::post('{device}/add-to-wish-list', [WishlistController::class, 'addWishList']);
        Route::post('{device}/add/to/wishlist', [WishlistController::class, 'addToWishlist']);
        Route::get('{device}/get-wish-list', [WishlistController::class, 'getWishList']);
        Route::get('{device}/remove-from-wish-list/{id}', [WishlistController::class, 'removeFromWishList']);
        Route::delete('{device}/remove-all-from-wish-list', [WishlistController::class, 'removeAllFromWishList']);
        // Review
        Route::post('{device}/add-review', [OttContentReviewController::class, 'store']);
        Route::get('{device}/review-remove/{id}', [OttContentReviewController::class, 'destroy']);
        Route::post('{device}/review-rection', [OttContentReviewController::class, 'reviewReaction']);
        // Subscription
        Route::get('{device}/available-subscription-plans-for-me', [FrontendController::class, 'availableSubscriptionPlansForMe']);
        Route::get('{device}/subscription-plan/{id}', [SubscriptionController::class, 'subscriptionDetail'])->withoutMiddleware(CheckAuthorization::class);
        Route::post('{device}/subscribe-plan/{planId}', [SubscriptionController::class, 'subscribeUser']);
        Route::post('{device}/ticket-subscribe-plan/{planId}', [SubscriptionController::class, 'subscribeUserWithTVOD']);
        Route::get('{device}/subscription-device-info', [FrontendController::class, 'subscriptionDeviceInfo']);
        Route::get('{device}/subscription-info', [FrontendController::class, 'subscriptionInfo']);
        Route::get('{device}/cancel-user-subscription/{user_subscription_plan_id}', [FrontendController::class, 'cancelUserSubscriptionPlan']);
        // Payment
        Route::get('{device}/payment-status/{paymentID}', [PaymentController::class, 'getPaymentStatus']);
    });
    /*====================================================================================================
    * These routes doesn't require authentication
    ====================================================================================================*/

    // Categories
    Route::get('{device}/category', [CategoryController::class, 'getRootCategory']);//
    Route::get('{device}/category/{slug}', [CategoryController::class, 'singleRootCategory']);
    Route::get('{device}/category/contents/{slug}', [CategoryController::class, 'rootCategoryContents']);
    // Sub Category Contents
    Route::get('{device}/sub-categories', [CategoryController::class, 'getSubCategories']);
    Route::get('{device}/sub-category/{slug}', [CategoryController::class, 'singleSubCategory']);
    Route::get('{device}/sub-category/contents/{slug}', [CategoryController::class, 'subCategoryContents']);
    // Sub Sub Category Contents
    Route::get('{device}/sub-sub-categories', [FrontendController::class, 'getSubSubCategories']);
    Route::get('{device}/sub-sub-category/{slug}', [FrontendController::class, 'singleSubSubCategory']);
    Route::get('{device}/single-ott-content/{uuid}', [OttContentController::class, 'singleOttContent']);
    Route::get('{device}/single-ott-content-reviews/{uuid}', [FrontendController::class, 'singleOttContentReview']);
    // Related Content
    Route::get('{device}/get-related-content/{uuid}', [OttContentController::class, 'getRelatedContent']);
    // Series
    Route::get('{device}/series', [SeriesController::class, 'getSeries']);
    Route::get('{device}/series/{slug}', [SeriesController::class, 'singleSeries']);
    Route::get('{device}/season/{id}', [SeasonController::class, 'singleSeason']);
    Route::get('{device}/sliders', [SliderController::class, 'sliders']);
    Route::get('{device}/category/sliders/{id}', [SliderController::class, 'categorySliders']);
    // Custom Home Page Section
    Route::get('{device}/frontend/custom/sections', [CustomContentSectionController::class, 'sectionFrontend']);
    Route::get('{device}/frontend/custom/section/{id}', [CustomContentSectionController::class, 'singleSectionFrontend']);
    Route::get('{device}/frontend/custom/section/sliders/{slug}', [CustomContentSectionController::class, 'singleSectionFrontendSliders']);
    Route::get('{device}/frontend/custom/sliders', [CustomSliderController::class, 'customSlidersFrontend']);
    Route::get('{device}/frontend/custom/slider/slug/{slug}', [CustomSliderController::class, 'customSingleSliderFrontend']);

    // Subscription Plans
    Route::get('{device}/available-subscription-plans', [SubscriptionController::class, 'availableSubscriptionPlans']);
    Route::get('{device}/available-payment-methods', [PaymentController::class, 'getAvailablePaymentMethods']);
    // Pages
    Route::get('{device}/pages', [PageController::class, 'getPages']);
    Route::get('{device}/single-page/{slug}', [PageController::class, 'getPage']);
    // Payment table update api with validation token
    Route::post('{device}/update-payment-table', [FrontendController::class, 'updatePaymentTable']);
    // Route::get('{device}/update-payment-table/parameter/{charge_id}/{validation_token}/{status}', [FrontendController::class, 'updatePaymentTableWithParameter']);
    //Elastic Search
    Route::post('{device}/content-search', [FrontendController::class, 'contentSearch']);
    //notification token
    Route::post('{device}/add-device-fcm-token', [FrontendController::class, 'addFCMToken']);
    Route::get('{device}/get-notifications', [FrontendController::class, 'getNotifications']);
    Route::get('{device}/remove-all-notifications', [FrontendController::class, 'removeAllNotification']);

    //search-contents
    Route::get('{device}/ott-content/search', [SearchController::class, 'searchContent']);
    //Gallery and Family Members
    Route::get('{device}/photo-gallery', [PhotoGalleryController::class, 'getGallery']);
    Route::get('{device}/family-members', [PhotoGalleryController::class, 'getFamilyMembers']);
    Route::get('{device}/family-gallery/{id}', [PhotoGalleryController::class, 'singleFamilyGallery']);
    //Encoding api
    Route::get('eoncoding/content-list', [EncodeController::class, 'contentList']);
});

// Temporary route, shouldn't in production
Route::get('playground/user/delete', function (\Illuminate\Http\Request $request) {
    if ($request->filled('x_email_x')) {
        $user = \App\Models\User::where('email', $request->input('x_email_x'))->first();
        if (!is_null($user)) {
            $user->delete();
            dd('User deleted successfully');
        }
        dd('Something went wrong');
    }
    dd('Something went wrong');
});

Route::get('playground/get_all_data/{model_name}', function ($model){
    try {

        $modelName = ucfirst($model);

        $qualifiedModel  = '\\App\\Models\\'. ucfirst($modelName);

        $modelOb = new $qualifiedModel();

        return response()->json([
            'table_name' => $modelOb->getTable(),
            'model_name' => $modelName,
            'data' => $modelOb->all(),
        ]);
    }catch (Exception $exception){
        dd($exception);
    }
});
