<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveCityRequest;
use App\Http\Requests\SavePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.city.form',
            'list'  => 'manager.pages.city.list'
        ];

        parent::__construct();
    }


    public function store(SaveCityRequest $request)
    {
        $data = $request->except('_token');
        $data['is_active'] = false;

        if ($request->isActive == "on"){
            $data['is_active'] = true;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }
        if($request->position){
            $data["position"] = (int)$request->position;
        }

        if ($request->id){
            City::updateOrCreate(['id'=>$request->id], $data );
        }else{
            City::create($data );
        }
        return $this->responseJson(true, __('informationSaved'));
    }

    function makeModel(): string
    {
        return City::class;
    }
}
