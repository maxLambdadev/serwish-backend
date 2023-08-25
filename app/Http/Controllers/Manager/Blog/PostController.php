<?php

namespace App\Http\Controllers\Manager\Blog;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SavePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.post.form',
            'list'  => 'manager.pages.post.list'
        ];

        parent::__construct();
    }

    public function form(Request $request)
    {
        $this->response['categories'] = Category::where('type','=','POSTS')->where('isActive','=',true)->get()->toArray();
        return parent::form($request);
    }

    public function store(SavePostRequest $request)
    {

        $data = $request->except('_token');
        $data['isActive'] = false;
        $data['author_id'] = Auth::user()->id;

        if ($request->isActive == "on"){
            $data['isActive'] = true;
        }
        if ($request->redirect) {
            $this->redirect = $request->redirect;
        }

        $post = Post::updateOrCreate(['id'=>$request->id], $data );
        if ($request->media){
            $post->images()->syncWithPivotValues($request->media , ['other_entity'=>Post::class ]);
        }

        if ($request->category) {
            $post->categories()->sync($request->category);
        }

        return $this->responseJson(true, __('informationSaved'));
    }

    function makeModel(): string
    {
        return Post::class;
    }
}
