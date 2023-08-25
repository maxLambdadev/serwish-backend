<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
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
URL::forceScheme('https');


Route::get('payment/callbac',function (Request $request){
    return redirect('https://serwish.ge/my-services');
});

//bog callback
Route::post('extra/payment/callback',function (Request $request){

    $payment = \App\Models\ServicePacket::where('payment_hash','=',$request->payment_hash)
        ->where('status','!=','success')
        ->first();

    $bog = new \J3dyy\BogPaymentWrapper\Request\BogRequest();

    try {
        if ($payment != null){
            $order = $bog->getOrder($payment->order_id);

            $userBalance = \App\Models\UserBalance::where('user_id','=', $payment->user_id)->first();

            if ($order->status == 'success'){
                $payment->status = 'success';

                $service = \App\Models\Services::findOrFail($payment->service_id);
                $service->packet_id = $payment->payable_packet_id;
                $service->packet_date = Carbon::now();

                $service->order_id = $payment->id;
                $service->save();

            }else{
                $payment->status = $request->status == null ? 'started' : $request->status;
            }

            $payment->save();

        }

    }catch (ClientException $e){

    }

    return response()->json(200);

});

Route::post('extra/payment/refund',function (Request $request){
    Storage::disk('local')->put('refund.txt', json_encode($request->all()));
});

Route::get('/logout',[LoginController::class,'processLogout'])->name('login');
Route::get('/login',[LoginController::class,'login'])->name('login');
Route::post('/login',[LoginController::class,'processLogin'])->name('processLogin');
Route::post('/login-code',[LoginController::class,'loginWithCode'])->name('loginWithCode');

Route::get('/register',[RegisterController::class,'register'])->name('register');
Route::post('/register',[RegisterController::class,'processRegister'])->name('processRegister');


//todo
Route::get('/', function(){
 return "00110";
});

