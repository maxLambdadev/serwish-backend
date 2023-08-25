<?php

namespace J3dyy\CrudHelper\Elements;

class EditButton extends Button
{

    public function __construct($key, $route = null, $bindForm = '' , $classes = 'btn btn-primary ', $content = '<span class="glyphicon glyphicon-pencil"></span>')
    {
        $classes .= ' modal-edit-form';
        $dataAttrs = [
            'route'=> $route,
            'bind-form' => $bindForm
        ];

        parent::__construct($key, $content, $classes, null, $dataAttrs, null);
    }
}
