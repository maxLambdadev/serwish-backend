<?php

namespace J3dyy\CrudHelper\Components\Form;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class Field extends Element
{

    protected $input;

    public function __construct($type, $key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct(ElementTypes::FIELD, $key, $content, $classes, $id, $dataAttrs);
    }

    function setInput(Element $element){
        $this->input = $element;
    }

    function transform(array $entity): View
    {
        return view('crudHelper::form.field',[
            'field'     => $this,
            'entity'    => $entity,
            'input'     => $this->input
        ]);
    }
}
