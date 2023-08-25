<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsCodes;
use App\Models\User;


class ApiController extends Controller
{
    protected array $responseBag = [];

    public function __construct(){
        $this->responseBag = [
            'code'  => 200,
            'body'  => '',
        ];
    }

    protected function response(int $code, $body = null, $error = null){
        $this->responseBag['code'] = $code;

        if ($body != null)
            $this->responseBag['body'] = $body;

        if ($error)
            $this->responseBag['message'] = $error;

        return $this->responsify();
    }


    protected function generateRandomCode(?User $user){
        $rand = rand(1000,9999);

        $check = SmsCodes::where('code','=',$rand)
            ->where('used','=',false);

        $user != null ? $check->where('user_id','=',$user->id) : '';

        $check = $check->first();

        if ($check != null){
            $this->generateRandomCode($user);
        }
        SmsCodes::create([
            'code'=>$rand,
            'user_id'=>$user != null ? $user->id : null
        ]);
        return $rand;
    }

    private function makeDefaultRespone(){
    }

    private function responsify(){
        return response()->json($this->responseBag);
    }


}


