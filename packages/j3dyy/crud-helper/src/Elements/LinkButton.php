<?php

namespace J3dyy\CrudHelper\Elements;

class LinkButton extends Button
{

    public function __construct($key, $route = null, $content = '<span class="glyphicon glyphicon-th-large"></span>', $classes = 'btn btn-transparent')
    {
        parent::__construct($key, $content, $classes, null, [], $route);
    }
}
