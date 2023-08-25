<?php

namespace J3dyy\CrudHelper\View;

use Illuminate\Contracts\View\View;
use J3dyy\CrudHelper\Components\Table\Table;
use J3dyy\CrudHelper\View\Model\Model;
use  \Illuminate\Database\Eloquent\Model as EloquentModel;
use J3dyy\CrudHelper\View\Model\ViewModel;
use JetBrains\PhpStorm\Pure;

class ListLayout extends Layout
{


    #[Pure]
    public function __construct(Model $viewModel)
    {
        parent::__construct($viewModel);
    }

    public static function of(array $list , array $columns = ['*'], EloquentModel $model = null, string $classes = 'table table-stripped', string $id = null ){
        //that means fetch column from database
        if (isset($columns[0]) && $columns[0] == '*' ) {
           $columns = self::parseAndCreateColumns($model);
        }

        $table = new Table($columns,$list, $classes, $id);
        return new ListLayout(
            new ViewModel($table)
        );
    }




    public function render(): View
    {
        return view('crudHelper::themes.table',[
            'viewModel' => $this->viewModel
        ]);
    }
}
