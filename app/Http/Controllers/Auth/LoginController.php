<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendSmsRequest;
use App\Models\Configuration;
use App\Models\SmsCodes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login()
    {
        return view('manager.auth.login');
    }

    /**
     * @throws ValidationException
     */
    public function processLogin(Request $request)
    {

        if (Auth::attempt($request->except('_token','remember'))){
            //email authentication disabled
//            $user = User::where('email','=',$request->email)->first();

//            if ($user == null){
                throw ValidationException::withMessages(['field_name' => 'Phone number Or Password not matches']);
//            }

//            Auth::login($user);
        }else{
            //phone auth here
            $user = User::where('phone_number','=',$request->email)->first();
            if ($user == null){
                throw ValidationException::withMessages(['field_name' => 'Phone number Or Password not matches']);
            }

            if (Hash::check($request->password, $user->password)) {
                $this->sendSms($user);
                return view('manager.auth.code',['user'=>$user]);
            }

            throw ValidationException::withMessages(['field_name' => 'Phone number Or Password not matches']);
        }

        if (Auth::user() == null){
            throw ValidationException::withMessages(['field_name' => 'Phone number Or Password not matches']);
        }

        return redirect()->route('manager.dashboard');
    }

    public function loginWithCode(Request $request){
        $user = User::findOrFail($request->user);
        $smsCode = SmsCodes::where('user_id','=',$user->id)
            ->where('code','=',$request->code)->first();

        if ($smsCode == null){
            throw ValidationException::withMessages(['field_name' => 'Phone number Or Password not matches']);
        }

        $smsCode->used = true;
        $smsCode->save();
        Auth::login($user);

        return redirect()->route('manager.dashboard');

    }

    public function processLogout(){
        Auth::logout();
        return redirect()->route('login');
    }


    private  function sendSms(User $user)
    {
        $code = $this->generateRandomCode($user);
        $conf = Configuration::where('key','=','SMS_OFFICE_BASIC')->first();
        $conf = json_decode($conf->data);
        $smsMessage = isset($conf->registerText) ? $conf->registerText : "{code}";

        if( preg_match( '!\{([^\)]+)\}!', $smsMessage, $match ) ){
            $matched = $match[1];
            $smsMessage = str_replace('{'.$matched.'}', $code, $smsMessage);
        }
        \J3dyy\SmsOfficeApi\SmsClient::instance()->to($user->phone_number)->message($smsMessage)->send();

    }

    private function generateRandomCode(User $user){


        $rand = rand(1000,9999);

        //clear old codes;
        foreach (SmsCodes::where('user_id','=',$user->id)->get() as $ch){
            $ch->destroy($ch->id);
        }

        $check = SmsCodes::where('code','=',$rand)
            ->where('used','=',false);




        $check = $check->first();

        if ($check != null){
            $this->generateRandomCode($user);
        }
        SmsCodes::create([
            'code'=>$rand,
            'user_id'=>$user->id
        ]);
        return $rand;
    }

}
