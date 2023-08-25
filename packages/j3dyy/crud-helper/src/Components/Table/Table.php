<?php
namespace J3dyy\CrudHelper\Components\Table;


use Illuminate\View\View;
use J3dyy\CrudHelper\Components\Component;

class Table extends Component
{

    public $columns = [];
    public $items = [];

    public function __construct(array $columns, array $items = [], string $classes = null, string $id = null)
    {
        parent::__construct($classes,$id);
        $this->columns = $columns;
        $this->items = $items;
    }


    public function render(): View
    {
        return view('crudHelper::table.table',['table'=>$this]);
    }
}
