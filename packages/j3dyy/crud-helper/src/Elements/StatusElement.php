<?php

namespace J3dyy\CrudHelper\Elements;

use Illuminate\View\View;

class StatusElement extends Element
{
    public function __construct($key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct(ElementTypes::BOOLEAN, $key, $content, $classes, $id, $dataAttrs);
    }


    function transform(array $entity): View
    {
        return view('crudHelper::elements.statusElement',[
            'statusElement' => $this,
            'entity' => $entity
        ]);
    }
}
