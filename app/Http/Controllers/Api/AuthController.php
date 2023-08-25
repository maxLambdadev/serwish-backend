<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiLoginRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\CheckSmsRequest;
use App\Http\Requests\Api\FilterableSupportRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\SendSmsRequest;
use App\Models\Blog\Category;
use App\Models\Configuration;
use App\Models\SmsCodes;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

/**
 *  Class AuthController
 *  @package App\Http\Controllers\Api
 *  @author jedy
 */
class AuthController extends ApiController
{

    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/oauth/token",
     *     summary="authorize user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/ApiAuthRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "token_type": "Bearer", "expires_in": 1296000,  "access_token": "", "refresh_token":"",}, summary="token response"),
     *         )
     *     )
     * )
     */
    public function login(){ }

    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/auth/login",
     *     summary="authorize user v2",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/ApiLoginRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "token_type": "Bearer", "expires_in": 1296000,  "access_token": "", "refresh_token":"",}, summary="token response"),
     *         )
     *     )
     * )
     */
    public function loginV2(ApiLoginRequest $request){

        $authType = 'phone';
        $user = User::where('phone_number','=',$request->username)->first();

        if ($user == null){
            return response("wrong user", 400);
        }

        if ($user->client_type !== 'employee' && $authType == 'email'){
            return response("wrong user", 400);
        }

        if (Hash::check($request->password,$user->password)){
            Auth::login($user);
            $token = $user->createToken('user');
            return $this->response(200,[
                'access_token'=> $token->accessToken,
                'token_type'=> 'Bearer'
            ]);
        }
        return response("wrong user", 400);
    }

    public function fbLogin(){

        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            $isUser = User::where('fb_id', $user->id)->first();
            if($isUser){
                Auth::login($isUser);
                if ($user->email !== null)
                    $isUser->email = $user->email;
                $isUser->name = $user->name;
//                $isUser->extraPic = $user->getAvatar();
                $isUser->save();
                $token = $isUser->createToken('user');
                $phone_verified = $isUser->phone_number?"true":"false";
                return Redirect::to("http://test-front.serwish.ge/?token=".$token->accessToken."&method=facebook&phone_number=".$phone_verified);
            }else{
                if ($user->email == null){
                        return abort(400, "you email not exists");
                }

                $createUser = User::where('email','=',$user->email)->first();

                if ($createUser == null){

                    $createUser = User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'extraPic'=> $user->getAvatar(),
                        'fb_id' => $user->id,
                        'password' => bcrypt('admin@123')
                    ]);

                }else{

                    if ($user->email !== null)
                        $createUser->email = $user->email;
                    $createUser->name = $user->name;
                    $createUser->fb_id = $user->id;
                    $createUser->extraPic = $user->getAvatar();
                    $createUser->save();
                }

                Auth::login($createUser);
                $token = $createUser->createToken('user');
                $phone_verified = $createUser->phone_number?"true":"false";
                return Redirect::to("http://test-front.serwish.ge/?token=".$token->accessToken."&method=facebook&phone_number=".$phone_verified);
            }

        } catch (Exception $exception) {
//            dd($exception->getMessage());
        }
    }

    public function fbLoginRedirect()
    {
        return Socialite::driver('facebook')
            ->setScopes(['public_profile', 'email'])
            ->with(['auth_type','rerequest'])->redirect();
    }

    public function googleLogin(){

        try {
            $user = Socialite::driver('google')->stateless()->user();
            // dump($user);
            $isUser = User::where('google_id', $user->id)->first();

            if($isUser){
                Auth::login($isUser);
                if ($user->email !== null)
                    $isUser->email = $user->email;
                $isUser->name = $user->name;
//                $isUser->extraPic = $user->getAvatar();
                $isUser->save();
                $token = $isUser->createToken('user');
                $phone_verified = $isUser->phone_number?"true":"false";
                return Redirect::to("http://test-front.serwish.ge/?token=".$token->accessToken."&method=google&phone_number=".$phone_verified);
            }else{
                if ($user->email == null){
                        return abort(400, "you email not exists");
                }

                $createUser = User::where('email','=',$user->email)->first();

                if ($createUser == null){

                    $createUser = User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'extraPic'=> $user->getAvatar(),
                        'google_id' => $user->id,
                        'password' => bcrypt('admin@123')
                    ]);

                }else{

                    if ($user->email !== null)
                        $createUser->email = $user->email;
                    $createUser->name = $user->name;
                    $createUser->google_id = $user->id;
                    $createUser->extraPic = $user->getAvatar();
                    $createUser->save();
                }

                Auth::login($createUser);
                $token = $createUser->createToken('user');
                $phone_verified = $createUser->phone_number?"true":"false";
                return Redirect::to("http://test-front.serwish.ge/?token=".$token->accessToken."&method=google&phone_number=".$phone_verified);
            }

        } catch (Exception $exception) {
//            dd($exception->getMessage());
        }
    }

    public function googleLoginRedirect()
    {
        return Socialite::driver('google')
        ->setScopes(['profile', 'email'])
            ->with(['auth_type','rerequest'])->redirect();
    }

    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/register",
     *     summary="register user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/RegisterRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "success":true}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request){

        $user = User::where('phone_number','=', $request->phone_number)->first();

        if ($user != null){
            return $this->response(401, "user this phone already exists");
        }

        $requestData = $request->all();
        $requestData['email'] = $request->phone_number.'@text.phone';
        $requestData['password'] = bcrypt($request->password);

        $code = SmsCodes::where('code','=', $request->sms_validation)->where('used','=',false)->first();

        $role = Role::findByName('client');

        if ($code != null){
            $request['is_active'] = true;
            $user = User::create($requestData);
            $user->assignRole($role);
            $code->user_id = $user->id;
            $code->used = true;
            $code->update();
            return $this->response(200,['message'=>'user created']);

        }else{
            return $this->response(401, error: "sms code invalid");
        }
    }



    /**
     * @OA\Post(
     *     tags={"3rd"},
     *     path="/api/send-sms",
     *     summary="register user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/SendSmsRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "code":200, "body":{"code":1234}}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function sendSms(SendSmsRequest $sendSmsRequest)
    {

        $user = User::where('phone_number','=', $sendSmsRequest->phone_number)->first();

        $code = $this->generateRandomCode($user);
        $conf = Configuration::where('key','=','SMS_OFFICE_BASIC')->first();
        $conf = json_decode($conf->data);
        $smsMessage = isset($conf->registerText) ? $conf->registerText : "{code}";

        if( preg_match( '!\{([^\)]+)\}!', $smsMessage, $match ) ){
            $matched = $match[1];
            $smsMessage = str_replace('{'.$matched.'}', $code, $smsMessage);
        }
        \J3dyy\SmsOfficeApi\SmsClient::instance()->to($sendSmsRequest->phone_number)->message($smsMessage)->send();
        return $this->response(200);

    }

    /**
     * @OA\Post(
     *     tags={"3rd"},
     *     path="/api/check-sms",
     *     summary="checking sms code",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/CheckSmsRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "code":200}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function checkPasswordSms(CheckSmsRequest $request){
        $user = User::where('phone_number','=', $request->phone_number)->first();

        if ($user == null){
            return $this->response(400, "user not found");
        }

        $code = SmsCodes::where('code','=', $request->sms_validation)
//            ->where('user_id','=',$user->id)
            ->where('used','=',false)->first();

        if ($code == null){
            return  $this->response(400,"wrong code");
        }

        $code->user_id = $user->id;

        if ($code != null){
            return $this->response(200, $user);
        }

    }

    /**
     * @OA\Post(
     *     tags={"3rd"},
     *     path="/api/change-sms",
     *     summary="change user password code",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      ref="#/components/schemas/ChangePasswordRequest"
     *             )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "code":200}, summary="some"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={ "errors": "arrayOf(errorKey:errorValue)"},  summary="some"),
     *         )
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request){
        $user = User::where('phone_number','=', $request->phone_number)->first();

        if ($user == null){
            return $this->response(400, "user not found");
        }

        $code = SmsCodes::where('code','=', $request->sms_validation)
            ->where('user_id','=',$user->id)
            ->where('used','=',false)->first();


        if ($code != null){
            $user->password = bcrypt($request->password);
            $user->save();
            $code->used = true;
            $code->save();
            return $this->response(200);
        }

        return  $this->response(400,"wrong code");
    }



}
