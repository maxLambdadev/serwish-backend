<?php

namespace App\Repository;

use J3dyy\LaravelLocalized\DB\Localized;
use J3dyy\LaravelLocalized\DB\Translatable;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make($this->make());
    }

    abstract function make(): string;
}
