<?php
namespace J3dyy\CrudHelper\View;

use J3dyy\CrudHelper\View\Model\Model;
use J3dyy\CrudHelper\View\Traits\ColumnHelper;

abstract class Layout implements Viewable
{
    use ColumnHelper;


    public function __construct( protected Model $viewModel )
    {
    }


}
