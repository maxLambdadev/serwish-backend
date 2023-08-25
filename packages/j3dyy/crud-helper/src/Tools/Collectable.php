<?php

namespace J3dyy\CrudHelper\Tools;


use Illuminate\Support\Collection;

class Collectable extends Collection
{

    public function __construct($items = [])
    {
        parent::__construct($items);
    }



}
