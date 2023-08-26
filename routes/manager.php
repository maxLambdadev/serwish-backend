<?php

use App\Http\Controllers\Manager\AdsController;
use App\Http\Controllers\Manager\Blog\CategoryController;
use App\Http\Controllers\Manager\CallRequestsController;
use App\Http\Controllers\Manager\CityController;
use App\Http\Controllers\Manager\Blog\PostController;
use App\Http\Controllers\Manager\ContactRequestsController;
use App\Http\Controllers\Manager\ConfigurationController;
use App\Http\Controllers\Manager\OrdersController;
use App\Http\Controllers\Manager\PaymentRequestsController;
use App\Http\Controllers\Manager\RolesController;
use App\Http\Controllers\Manager\FaqController;
use App\Http\Controllers\Manager\Services\CommentReviewController;
use App\Http\Controllers\Manager\Services\ServiceCategoryController;
use App\Http\Controllers\Manager\Services\ServiceController;
use App\Http\Controllers\Manager\SliderController;
use App\Http\Controllers\Manager\UsersController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LocalesController;
use \App\Http\Controllers\Manager\PayablePacketController;

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


Route::middleware(['auth','role:administrator|moderator|merchant|sales|warehouse|seo სპეციალისტი|ბლოგერი'])->name('manager.')->group(function (){

    Route::prefix('payable-packet')->name('payable-packet.')->group(function (){
        Route::get('/', [PayablePacketController::class, 'list'])->name('list');

        Route::get('/edit/{id}', [PayablePacketController::class, 'form'])->name('edit');
        Route::get('/create', [PayablePacketController::class, 'form'])->name('create');
        Route::post('/save', [PayablePacketController::class, 'store'])->name('store');

        Route::delete('/delete/{id}', [PayablePacketController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [PayablePacketController::class, 'bulkDelete'])->name('bulk.delete');
    });


    Route::prefix('media')->name('media.')->group(function (){
        Route::post('/store',[MediaController::class,'store'])->name('store');
        Route::post('/update',[MediaController::class,'update'])->name('update');
        Route::get('/explore',[MediaController::class,'explore'])->name('explore');
        Route::get('/list',[MediaController::class,'list'])->name('list');

        Route::post('/trash',[MediaController::class,'trash'])->name('trash');
        Route::post('/force-delete',[MediaController::class,'delete'])->name('trash');

        Route::post('/make-default', [MediaController::class, 'setDefaultResource'])->name('makedefault');
        Route::post('/detach', [MediaController::class, 'detach'])->name('detach');
    });

    Route::get('/', [ServiceController::class, 'list'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function (){
        Route::get('/', [UsersController::class, 'list'])->name('index');
        Route::get('/contacts', [UsersController::class, 'contacts'])->name('contacts');
        Route::post('/contact/send-sms', [UsersController::class, 'sendSms'])->name('sendSms');
        Route::post('/contact/send-custom-sms', [UsersController::class, 'sendCustomSms'])->name('sendCustomSms');
        Route::post('/contact/users/by/categories', [UsersController::class, 'getUsersByCategories'])->name('getUsersByCategories');



        Route::get('/show/{id}', [UsersController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [UsersController::class, 'form'])->name('edit');
        Route::get('/create', [UsersController::class, 'form'])->name('create');
        Route::post('/save', [UsersController::class, 'store'])->name('store');
        Route::get('/export', [UsersController::class, 'export'])->name('export');

        Route::delete('/delete/{id}', [UsersController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [UsersController::class, 'bulkDelete'])->name('bulk.delete');
        Route::post('/specialist/update', [UsersController::class, 'changeSpecialistState'])->name('specialist.status.change');
    });

    Route::post('/roles/change-permission', [RolesController::class, 'updatePermission'])->name('roles.update.permission');
    Route::post('/roles/bulk/delete', [RolesController::class, 'bulkDelete'])->name('roles.bulk.delete');
    Route::resource('roles', RolesController::class);

    Route::prefix('blog')->name('blog.')->group(function (){

        Route::prefix('category')->name('category.')->group(function (){
            Route::get('/', [CategoryController::class, 'list'])->name('index');

            Route::get('/edit/{id}', [CategoryController::class, 'form'])->name('edit');
            Route::get('/create', [CategoryController::class, 'form'])->name('create');
            Route::post('/save', [CategoryController::class, 'store'])->name('store');

            Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/bulk/delete', [CategoryController::class, 'bulkDelete'])->name('bulk.delete');
        });

        Route::prefix('post')->name('post.')->group(function (){
            Route::get('/', [PostController::class, 'list'])->name('index');

            Route::get('/edit/{id}', [PostController::class, 'form'])->name('edit');
            Route::get('/create', [PostController::class, 'form'])->name('create');
            Route::post('/save', [PostController::class, 'store'])->name('store');

            Route::delete('/delete/{id}', [PostController::class, 'destroy'])->name('destroy');
            Route::post('/bulk/delete', [PostController::class, 'bulkDelete'])->name('bulk.delete');
        });

    });


    /**
     * services route
     */
    Route::prefix('services')->name('services.')->group(function () {
        Route::prefix('category')->name('category.')->group(function (){
            Route::get('/', [ServiceCategoryController::class, 'list'])->name('index');
            Route::get('/statistics', [ServiceCategoryController::class, 'statistics'])->name('statistics');

            Route::get('/edit/{id}', [ServiceCategoryController::class, 'form'])->name('edit');
            Route::get('/create', [ServiceCategoryController::class, 'form'])->name('create');
            Route::post('/save', [ServiceCategoryController::class, 'store'])->name('store');
            Route::get('/export', [ServiceCategoryController::class, 'export'])->name('export');

            Route::delete('/delete/{id}', [ServiceCategoryController::class, 'destroy'])->name('destroy');
            Route::post('/bulk/delete', [ServiceCategoryController::class, 'bulkDelete'])->name('bulk.delete');
        });

        Route::prefix('review')->name('review.')->group(function(){
            Route::get('/', [CommentReviewController::class, 'list'])->name('index');
            Route::get('/show/{id}', [CommentReviewController::class, 'show'])->name('show');

            Route::delete('/delete/{id}', [CommentReviewController::class, 'destroy'])->name('destroy');
            Route::post('/bulk/delete', [CommentReviewController::class, 'bulkDelete'])->name('bulk.delete');
        });

        Route::prefix('service')->name('service.')->group(function (){
            Route::get('/', [ServiceController::class, 'list'])->name('index');
            Route::post('/', [ServiceController::class, 'list'])->name('index');
            Route::get('/my-approvals', [ServiceController::class, 'myApprovals'])->name('my-approvals');

            Route::get('/serwish-quality/{id}/{on}', [ServiceController::class, 'hasSerwishQuality'])->name('hasSerwishQuality');
            Route::get('/show/{id}', [ServiceController::class, 'form'])->name('show');
            Route::get('/create', [ServiceController::class, 'form'])->name('create');
            Route::post('/save', [ServiceController::class, 'store'])->name('store');
            Route::post('/update-service-name', [ServiceController::class, 'updateServiceName'])->name('update-service-name');
            Route::get('/export', [ServiceController::class, 'export'])->name('export');

            Route::get('/delete/{id}', [ServiceController::class, 'remove'])->name('delete');
            Route::delete('/delete/{id}', [ServiceController::class, 'destroy'])->name('destroy');
            Route::post('/bulk/delete', [ServiceController::class, 'bulkDelete'])->name('bulk.delete');
        });
    });

    /**
     * end services route
     */
    Route::prefix('slider')->name('slider.')->group(function (){
        Route::get('/', [SliderController::class, 'list'])->name('list');

        Route::get('/edit/{id}', [SliderController::class, 'form'])->name('edit');
        Route::get('/create', [SliderController::class, 'form'])->name('create');
        Route::post('/save', [SliderController::class, 'store'])->name('store');

        Route::delete('/delete/{id}', [SliderController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [SliderController::class, 'bulkDelete'])->name('bulk.delete');
    });

    Route::prefix('faq')->name('faq.')->group(function (){
        Route::get('/', [FaqController::class, 'list'])->name('list');

        Route::get('/edit/{id}', [FaqController::class, 'form'])->name('edit');
        Route::get('/create', [FaqController::class, 'form'])->name('create');
        Route::post('/save', [FaqController::class, 'store'])->name('store');

        Route::delete('/delete/{id}', [FaqController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [FaqController::class, 'bulkDelete'])->name('bulk.delete');
    });

    Route::prefix('ads')->name('ads.')->group(function (){
        Route::get('/', [AdsController::class, 'list'])->name('list');

        Route::get('/edit/{id}', [AdsController::class, 'form'])->name('edit');
        Route::get('/create', [AdsController::class, 'form'])->name('create');
        Route::post('/save', [AdsController::class, 'store'])->name('store');

        Route::delete('/delete/{id}', [AdsController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [AdsController::class, 'bulkDelete'])->name('bulk.delete');
    });

    Route::prefix('city')->name('city.')->group(function (){
        Route::get('/', [CityController::class, 'list'])->name('list');

        Route::get('/edit/{id}', [CityController::class, 'form'])->name('edit');
        Route::get('/create', [CityController::class, 'form'])->name('create');
        Route::post('/save', [CityController::class, 'store'])->name('store');

        Route::delete('/delete/{id}', [CityController::class, 'destroy'])->name('destroy');
        Route::post('/bulk/delete', [CityController::class, 'bulkDelete'])->name('bulk.delete');
    });


    Route::prefix('call-requests')->name('call-requests.')->group(function (){
        Route::get('/', [CallRequestsController::class, 'list'])->name('list');
        Route::post('/smsoffice/basic', [CallRequestsController::class, 'isCalled'])->name('is-called');
    });

    Route::prefix('contact-requests')->name('contact-requests.')->group(function (){
        Route::get('/', [ContactRequestsController::class, 'list'])->name('list');
        Route::post('/contact/basic', [ContactRequestsController::class, 'isSeen'])->name('seen');
    });

    Route::prefix('payment-requests')->name('payment-requests.')->group(function (){
        Route::get('/', [PaymentRequestsController::class, 'list'])->name('list');
        Route::post('/change-status', [PaymentRequestsController::class, 'changeStatus'])->name('change-status');
    });


    Route::prefix('orders')->name('orders.')->group(function (){
        Route::get('/', [OrdersController::class, 'list'])->name('list');
        Route::get('/export', [OrdersController::class, 'export'])->name('export');
        Route::get('/{id}', [OrdersController::class, 'show'])->name('show');
        Route::delete('/destroy/{id}', [OrdersController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('configuration')->name('configuration.')->group(function (){
        Route::get('/', [UsersController::class, 'basicConfiguration'])->name('basic');

        Route::prefix('locales')->name('locales.')->group(function (){
            Route::get('/', [LocalesController::class, 'list'])->name('index');

            Route::get('/edit/{id}', [LocalesController::class, 'form'])->name('edit');
            Route::get('/create', [LocalesController::class, 'form'])->name('create');
            Route::post('/save', [LocalesController::class, 'store'])->name('store');

            Route::delete('/delete/{id}', [LocalesController::class, 'destroy'])->name('destroy');
        });

        Route::get('/smsoffice/basic', [ConfigurationController::class, 'smsOfficeBasic'])->name('smsoffice.basic');
        Route::post('/smsoffice/basic', [ConfigurationController::class, 'smsOfficeBasicSave'])->name('smsoffice.basic.save');
    });

});
