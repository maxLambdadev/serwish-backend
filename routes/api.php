<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServiceController;
use App\Models\Orders\OrderGroups;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['cors'])->group(function () {

    Route::get('/packet-list',[OrdersController::class,'packetList']);

    Route::prefix('categories')->name('categories.')->group(function (){
        Route::get('/list',[CategoryController::class, 'list'])->name('list');
    });

    Route::post('/find-payment-by-hash', [OrdersController::class,'findPaymentByHash']);

    Route::group(['middleware' => ['web']], function () {
        Route::get('/test',function (){

            $group = OrderGroups::findOrFail(180);
            $client = new \GuzzleHttp\Client([
                'base_uri'=> "https://ws.serwish.ge/uploaded-chat-image"
            ]);
            $resp = $client->request('POST',"https://ws.serwish.ge/uploaded-chat-image", [
                'json'=> [
                    'group'=> $group,
                    'message'=> "media path",
                    'from'=>"some",
                ]
            ]);
            dd($resp);
        });

        Route::get('auth/facebook', [AuthController::class, 'fbLoginRedirect']);
        Route::get('auth/facebook/callback', [AuthController::class, 'fbLogin']);

        Route::get('auth/google', [AuthController::class, 'googleLoginRedirect']);
        Route::get('auth/google/callback', [AuthController::class, 'googleLogin']);
    });

    Route::post('auth/login', [AuthController::class, 'loginV2']);

    Route::get('/sxva-ragac',[CommonController::class,'ads'])->name('slider.ads');
    Route::get('/slider',[CommonController::class,'slider'])->name('slider.list');
    Route::get('/faq',[CommonController::class,'faq'])->name('faq.list');

    Route::post('/make/call',[CommonController::class,'makeCallRequest'])->name('call.request');
    Route::post('/make/contact',[CommonController::class,'makeContactRequests'])->name('contact.request');

    Route::post('/send-sms',[AuthController::class,'sendSms'])->name('sendSms');
    Route::post('/check-sms',[AuthController::class,'checkPasswordSms'])->name('checkSms');
    Route::post('/change-sms',[AuthController::class,'changePassword'])->name('change-password');

    Route::post('/register',[AuthController::class,'register'])->name('register');

    Route::post('/search', [ServiceController::class, 'search'])->name('search');


    Route::prefix('posts')->name('posts.')->group(function (){
        Route::get('/list',[PostsController::class, 'list'])->name('list');
        Route::get('/{id}/{locale}',[PostsController::class, 'single'])->name('single');
    });


    Route::prefix('services')->name('services.')->group(function () {
        Route::post('/count/click', [ServiceController::class,'countButtonClick']);

        Route::get('/tags/search', [ServiceController::class,'findTag']);
        Route::get('/city/list', [ServiceController::class,'cityList']);
        Route::get('/city/search', [ServiceController::class,'findCity']);

        Route::get('/list', [ServiceController::class, 'list'])->name('list');
        Route::get('/call/review', [ServiceController::class, 'phoneCallback'])->name('list');
        Route::get('/call/specialist/review', [ServiceController::class, 'phoneSpecialistCallback'])->name('splist');
        Route::get('/{id}/{locale}', [ServiceController::class, 'single'])->name('single');
    });

    Route::prefix('specialists')->name('specialists.')->group(function () {
        Route::get('/list', [UserController::class, 'getSpecialists'])->name('list');
        Route::get('/{id}/reviews', [UserController::class, 'specialistReviews'])->name('spec.reviews');
        Route::get('/{id}/services', [UserController::class, 'getServices'])->name('spec.services');
        Route::get('/{id}', [UserController::class, 'getSpecialist'])->name('spec');
    });

    Route::middleware('auth:api')->group(function (){
        Route::prefix('services')->name('services.')->group(function (){
            Route::post('/create-basic', [ServiceController::class,'createService']);
            Route::post('/update-basic', [ServiceController::class,'updateBasic']);
            Route::post('/add-images', [ServiceController::class,'attachImages']);
//            Route::post('/add-images', [ServiceController::class,'uploadLargeFiles']);
            Route::post('/remove-images', [ServiceController::class,'removeImage']);

            Route::post('/tags/sync', [ServiceController::class,'addTags']);
            Route::post('/city/sync', [ServiceController::class,'addCity']);


            Route::prefix('orders')->name('orders.')->group(function (){
                Route::post('order/messages', [OrdersController::class,'messages']);
                Route::get('order/unread-messages', [OrdersController::class,'unreadMessages']);
                Route::post('order/message/attachment', [OrdersController::class, 'uploadMessageAttachment']);

                Route::get('get-orders/list', [OrdersController::class,'getOrders']);
                Route::get('get-order/{id}', [OrdersController::class,'getOrder']);
                Route::get('get-payments/list', [OrdersController::class,'getPayments']);

                Route::post('start-order', [OrdersController::class,'startOrder']);

                Route::post('create-payment', [OrdersController::class,'createPaymentRequest']);

                Route::post('request-withdraw',[OrdersController::class,'requestWithdrawal']);

                Route::get('my-payment/my-withdrawals',[OrdersController::class,'userWithdrawals']);
            });
        });

        Route::prefix('user')->name('user.')->group(function (){
            Route::post('/me',[UserController::class, 'me'])->name('me');
            Route::post('/update',[UserController::class, 'updateUser'])->name('update');
            Route::post('/add-profile-pic',[UserController::class, 'setProfilePic'])->name('profilePic');
            Route::post('/services',[UserController::class, 'services'])->name('services');
        });
        Route::prefix('specialists')->name('specialists.')->group(function () {
            Route::post('/add-review', [UserController::class, 'addReview'])->name('addReview');
        });
    });

});





