<?php

namespace J3dyy\CrudHelper\Components\Form\Types;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class Checkbox extends Input
{
    public $validator = '';
    public $value = '';

    public function __construct($type = '', $key = '', $content = '', $classes = null, $id = null, array $dataAttrs = [])
    {
        $this->value = $content;
        parent::__construct($type, $key, $content, $classes, $id, $dataAttrs);
    }

    function transform(array $entity): View
    {
        return view('crudHelper::form.textarea',[
            'textarea' => $this,
            'entity' => $entity
        ]);
    }
}
