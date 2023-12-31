<?php
namespace App\Http\Controllers\Manager;

use App\Exports\ServiceCategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveBlogCategoryRequest;
use App\Models\Blog\Category;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;


class FaqController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.faq.form',
            'list'  => 'manager.pages.faq.list'
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
        $data['is_active'] = false;

        if ($request->is_active == "on"){
            $data['is_active'] = true;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        if ($request->id){
            $category = Faq::updateOrCreate(['id'=>$request->id], $data );
        }else{
            $category = Faq::create($data );
        }

        if ($request->media){
            $category->images()->detach();
            $category->images()->attach((int) $request->media , [ 'other_entity'=>Faq::class ]);
        }

        Redis::del('faq');

        return $this->responseJson(true, __('informationSaved'));
    }

    public function destroy($id)
    {
        Redis::del('faq');
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }

    public function bulkDelete(Request $request)
    {
        Redis::del('faq');
        return parent::bulkDelete($request); // TODO: Change the autogenerated stub
    }

    public function export(Request $request)
    {
        return Excel::download(new ServiceCategoryExport($request->exportIds), 'categories.xlsx');

    }


    function makeModel(): string
    {
        return Faq::class;
    }
}


