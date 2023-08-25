<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SaveLocalesRequest;
use App\Models\Blog\Post;
use App\Models\Locales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocalesController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.configuration.locales.form',
            'list'  => 'manager.pages.configuration.locales.list'
        ];

        parent::__construct();
    }


    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {
        return parent::list($request, null);
    }

    public function store(SaveLocalesRequest $request)
    {
        DB::transaction(function () use ($request) {
          $data = $request->except('_token');
          $data['is_active'] = false;;
          $data['is_default'] = false;;

          if ($request->is_default == "on"){
              //overwrite previous default locale
              $defaultLocale = Locales::where('is_default','=',true)->first();
              $defaultLocale->is_default = false;
              $defaultLocale->save();
              $data['is_default'] = true;
          }

          if ($request->is_active == "on"){
              $data['is_active'] = true;
          }
          if ($request->redirect) {
              $this->redirect = $request->redirect;
          }

          $post = Locales::updateOrCreate(['id'=>$request->id], $data );
          if ($request->media){
              $post->images()->syncWithPivotValues($request->media , ['other_entity'=>Post::class ]);
          }

          if ($request->category) {
              $post->categories()->sync($request->category);
          }
          return $post;
      });
        return $this->responseJson(true, __('informationSaved'));
    }


    public function destroy($id)
    {
        $entity = Locales::findOrFail($id);
        if ($entity->is_default){
            return abort(400,"მთავარი ენის წაშლა შეუძლებელია");
        }
        return parent::destroy($id);
    }

    function makeModel(): string
    {
        return Locales::class;
    }
}
