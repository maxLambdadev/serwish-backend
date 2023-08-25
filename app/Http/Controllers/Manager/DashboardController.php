<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    function index()
    {
        $users = User::orderBy('id','DESC')->take(5)->get();
        return view('manager.dashboard', ['users'=>$users]);
    }

}
