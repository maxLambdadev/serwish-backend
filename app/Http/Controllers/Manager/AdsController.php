<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseController;
use App\Http\Requests\MakeIsCalledRequest;
use App\Http\Requests\SaveCityRequest;
use App\Http\Requests\SavePostRequest;
use App\Models\Ads;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\CallRequests;
use App\Models\City;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdsController extends BaseController
{


    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.ads.form',
            'list'  => 'manager.pages.ads.list'
        ];

        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {

        $this->model = $this->model->orderBy('order','DESC');
        return parent::list($request, $ordering); // TODO: Change the autogenerated stub
    }

    public function store(Request $request)
    {
        $data = $request->except('_token','id');
        $data['is_active'] = false;

        if ($request->is_active == "on"){
            $data['is_active'] = true;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        if ($request->id){
            $category = Ads::updateOrCreate(['id'=>$request->id], $data );
        }else{
            $category = Ads::create($data );
        }

        if ($request->media){
            $category->images()->detach();
            $category->images()->attach((int) $request->media , [ 'other_entity'=>Ads::class ]);
        }

        return $this->responseJson(true, __('informationSaved'));
    }


    function makeModel(): string
    {
        return Ads::class;
    }
}
