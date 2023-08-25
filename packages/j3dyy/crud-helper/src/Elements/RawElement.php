<?php

namespace J3dyy\CrudHelper\Elements;

use Illuminate\View\View;

class RawElement extends Element
{

    public function __construct($key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct(ElementTypes::RAW, $key, $content, $classes, $id, $dataAttrs);
    }

    function transform(array $entity): View
    {
        //not renderable
        return view('');
    }
}
