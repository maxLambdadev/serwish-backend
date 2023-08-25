<?php
namespace App\Http\Controllers\Manager\Blog;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveBlogCategoryRequest;
use App\Models\Blog\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.category.form',
            'list'  => 'manager.pages.category.list'
        ];

        parent::__construct();
    }


    public function list(Request $request , ?array $ordering = ['created_at','DESC'])
    {
        $this->model = $this->model->where('type','POSTS');
        return parent::list($request);
    }

    public function store(SaveBlogCategoryRequest $request)
    {
        $data = $request->except('_token','id');
        $data['type'] = 'POSTS';
        $data['isActive'] = false;
        $data['author_id'] = Auth::user()->id;

        if ($request->isActive == "on"){
            $data['isActive'] = true;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        if ($request->id){
            $category = Category::updateOrCreate(['id'=>$request->id], $data );
        }else{
            $category = Category::create($data );
        }
        if ($request->media){
            $category->images()->detach();
            $category->images()->attach((int) $request->media , [ 'other_entity'=>Category::class ]);
        }

        return $this->responseJson(true, __('informationSaved'));
    }

    function makeModel(): string
    {
        return Category::class;
    }
}

