<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseController;
use App\Models\Menu;
use Illuminate\Http\Request;
use J3dyy\CrudHelper\CrudController;

class MenuController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
          'list'    => 'manager.pages.menu.list',
        ];
        parent::__construct();
    }

    function makeModel(): string
    {
        return Menu::class;
    }
}
