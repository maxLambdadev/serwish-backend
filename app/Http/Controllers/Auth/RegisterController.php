<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register()
    {
        return view('manager.auth.register');
    }

    public function processRegister(RegisterRequest $request)
    {
        if ($request->password == $request->rePassword){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
        }
        return redirect()->route('login')->with('status','User Registered');
    }
}
