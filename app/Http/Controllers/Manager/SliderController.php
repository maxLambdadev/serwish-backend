<?php
namespace App\Http\Controllers\Manager;

use App\Exports\ServiceCategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveBlogCategoryRequest;
use App\Models\Blog\Category;
use App\Models\Faq;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class SliderController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.slider.form',
            'list'  => 'manager.pages.slider.list'
        ];

        parent::__construct();
    }

    public function list(Request $request , ?array $ordering = ['created_at','DESC'])
    {
        return parent::list($request);
    }

    public function store(SaveBlogCategoryRequest $request)
    {
        $data = $request->except('_token','id');
        $data['isActive'] = false;

        if (!$request->sort_order){
            $data['sort_order'] = 0;
        }

        if ($request->isActive == "on"){
            $data['isActive'] = true;
        }
        if ($request->showMoreBtn){
            $data["showMoreBtn"] = $request->showMoreBtn;
        }else{
            $data['showMoreBtn'] = null;
        }

        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        if ($request->id){
            $category = Slider::updateOrCreate(['id'=>$request->id], $data );
        }else{
            $category = Slider::create($data );
        }

        if ($request->media){
            $category->images()->detach();
            $category->images()->attach((int) $request->media , [ 'other_entity'=>Slider::class ]);
        }

        return $this->responseJson(true, __('informationSaved'));
    }




    function makeModel(): string
    {
        return Slider::class;
    }
}


