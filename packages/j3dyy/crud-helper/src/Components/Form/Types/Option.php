<?php

namespace J3dyy\CrudHelper\Components\Form\Types;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class Option extends Input
{
    public $selected = false;

    public function __construct($key,$content, bool $selected = false, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct(ElementTypes::OPTION, $key, $content, $classes, $id, $dataAttrs);
        $this->selected = $selected;
    }

    function transform(array $entity): View
    {
        //todo
        return view('administration');
    }
}
