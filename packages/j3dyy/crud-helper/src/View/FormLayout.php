<?php

namespace J3dyy\CrudHelper\View;

use Illuminate\Contracts\View\View;
use J3dyy\CrudHelper\Components\Form\FormBuilder;
use J3dyy\CrudHelper\View\Model\Model;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use J3dyy\CrudHelper\View\Model\ViewModel;
use J3dyy\LaravelLocalized\DB\Localized;
use J3dyy\LaravelLocalized\Tools\TranslationTool;
use JetBrains\PhpStorm\Pure;

class FormLayout extends Layout
{

    #[Pure]
    public function __construct(Model $viewModel)
    {
        parent::__construct($viewModel);
    }

    public static function of( EloquentModel $model = null, string $classes = null, string $id = null ){
        $relatedModels = [$model];

        //$form = new Form('','',$classes,$id);

        //fetch fields from model or db
        //out of the box laravel-localized package support
        if ($model instanceof Localized){
            $relatedModels[] =  TranslationTool::createTranslation($model);
        }
        $formBuilder = new FormBuilder('','',$classes,$id);
        $formBuilder->fields(...$relatedModels);

        return new FormLayout(
            new ViewModel($formBuilder->build())
        );
    }



    public function render(): View
    {
        return view('crudHelper::themes.form',[
            'viewModel' => $this->viewModel
        ]);
    }
}
