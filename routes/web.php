


<?php

use App\Http\Controllers\BackendLaravel\AdminAuthController;
use App\Http\Controllers\BackendLaravel\AdminController;
use App\Http\Controllers\BackendLaravel\FrontendCustomContentSectionController;
use App\Http\Controllers\BackendLaravel\OttContentController;
use App\Http\Controllers\BackendLaravel\OttSeriesController;
use App\Http\Controllers\BackendLaravel\OttSliderController;
use App\Http\Controllers\BackendLaravel\RoleController;
use App\Http\Controllers\BackendLaravel\RootCategoryController;
use App\Http\Controllers\BackendLaravel\SubCategoryController;
use App\Http\Controllers\BackendLaravel\SubSubCategoryController;
use App\Http\Middleware\AdminAccess;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin-login', [AdminAuthController::class, 'loginPage'])->name('admin.login');
Route::post('/admin-login-action', [AdminAuthController::class, 'loginAction'])->name('admin.action-login');
Route::get('/admin-register', [AdminAuthController::class, 'registerPage'])->name('admin.register');
Route::post('/admin-register-action', [AdminAuthController::class, 'registerAction'])->name('admin.post-register');
Route::get('/admin-logout', [AdminAuthController::class, 'logoutAdmin'])->name('admin.logout');



Route::group([
    'middleware' => AdminAccess::class
], function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');

    Route::resource('root-category', RootCategoryController::class);
    Route::get('/root-category-delete/{id}', [RootCategoryController::class, 'destroy'])->name('root-category.delete');



    Route::group(['prefix' => 'admin'], function () {
        Route::resource('sub-category', SubCategoryController::class);
        Route::get('/sub-category-delete/{id}', [SubCategoryController::class, 'destroy'])->name('sub-category.delete');

        Route::resource('sub-sub-category', SubSubCategoryController::class);
        Route::get('/sub-sub-category-delete/{id}', [SubSubCategoryController::class, 'destroy'])->name('sub-sub-category.delete');

        //filter sub_category by root_category_id
        Route::get('filter-sub_category/{id}', [SubSubCategoryController::class, 'filterSubCategory']);
        Route::get('filter-sub_sub_category/{id}', [OttContentController::class, 'filterSubSubCategory']);
        Route::get('filter-sub_category-with-content/{id}', [SubSubCategoryController::class, 'filterSubCategoryWithContent']);
        Route::get('filter-sub_sub_category-with-content/{id}', [OttContentController::class, 'filterSubSubCategoryWithContent']);
        Route::get('filter-sub_sub_category-content/{id}', [OttContentController::class, 'filterSubSubCategoryContent']);


        Route::get('filter-series', [OttContentController::class, 'filterSeries']);




//        Route::resource('ott-slider', OttSliderController::class);
        Route::get('/ott-slider-delete/{id}', [OttSliderController::class, 'destroy'])->name('ott-slider.delete');

        Route::resource('ott-content', OttContentController::class);
        Route::get('/ott-content-delete/{id}', [OttContentController::class, 'destroy'])->name('ott-content.delete');
        Route::get('/ott-content/upload-subtitle/{id}', [OttContentController::class, 'getUploadMediaSubtitle'])->name('upload.subtitle');
        Route::post('/ott-content/upload-subtitle', [OttContentController::class, 'storeUploadMediaSubtitle'])->name('ott-content-subtitle.store');

        // Route::post('/ott-content/file-upload', [OttContentController::class, 'storeFile'])->name('file.upload');
        //content -media
        Route::get('/ott-content/upload-media/{id}', [OttContentController::class, 'getUploadMedia'])->name('upload.media');
        Route::get('/delete-media/{id}', [OttContentController::class, 'deleteContentSource'])->name('delete-media');
        Route::post('/upload-media-file', [OttContentController::class, 'uploadMedia'])->name('files.upload.large');
        // //content -trailer
        Route::get('/ott-content/upload-trailer/{id}', [OttContentController::class, 'getUploadTrailer'])->name('upload.Trailer');
        // Route::get('/delete-trailer/{id}', [OttContentController::class, 'deleteContentSource'])->name('delete-Trailer');
        Route::post('/upload-trailer-file', [OttContentController::class, 'uploadTrailer'])->name('files.uploadtrailer');

//        Route::resource('ott-series', OttSeriesController::class);
        Route::get('/ott-series-delete/{id}', [OttSeriesController::class, 'destroy'])->name('ott-series.delete');

        Route::resource('frontend-custom-content-section', FrontendCustomContentSectionController::class);
        Route::get('/frontend-custom-content-section-delete/{id}', [FrontendCustomContentSectionController::class, 'destroy'])->name('content-section.delete');
        Route::get('/frontend-custom-content-section-add-content/{id}', [FrontendCustomContentSectionController::class, 'addContent'])->name('frontend-custom-content-section.addContent');
        Route::post('/add-frontend-custom-content-section-contents', [FrontendCustomContentSectionController::class, 'AddCustomContents'])->name('add_custom_content_section_content');
        Route::get('/edit-frontend-custom-content-section-contents/{id}', [FrontendCustomContentSectionController::class, 'editCustomContent'])->name('edit_custom_content_section_content');
        Route::post('/edit-frontend-custom-content-section-contents', [FrontendCustomContentSectionController::class, 'updateCustomContent'])->name('update_custom_content_section_content');
        Route::get('/frontend-custom-content-section-content-delete/{id}', [FrontendCustomContentSectionController::class, 'destroyCustomContent'])->name('destroy_custom_content_section_content');

        Route::resource('roles', RoleController::class);
        Route::get('/role-delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');
        Route::resource('admin', AdminController::class);
        Route::get('/admin-delete/{id}', [AdminController::class, 'destroy'])->name('admin_delete');
    });
});
