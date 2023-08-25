<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    protected $themePath = 'themes.martve.';


    public function index()
    {

        return view($this->themePath.'index');
    }

    public function teams()
    {

        return view($this->themePath.'teams');
    }

    public function calendar()
    {
        return view($this->themePath.'calendar');
    }

    public function table()
    {
        return view($this->themePath.'table');
    }

    public function players()
    {
        return view($this->themePath.'players');
    }

    public function player($id)
    {

        return view($this->themePath.'player');
    }

    public function staffs()
    {

        return view($this->themePath.'staffs');
    }

    public function staff($id)
    {
        return view($this->themePath.'staff');
    }

    public function match($id)
    {

        return view($this->themePath.'single-match');
    }


}
