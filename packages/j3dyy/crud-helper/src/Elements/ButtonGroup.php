<?php

namespace J3dyy\CrudHelper\Elements;

use Illuminate\View\View;

class ButtonGroup extends Button
{
    public $buttonGroups;

    public function __construct(array $buttons , $key, $content, $classes = null, $id = null, array $dataAttrs = [], string $link = null)
    {
        $this->buttonGroups = $buttons;
        parent::__construct($key, $content, $classes, $id, $dataAttrs, $link);
    }


    function transform(array $entity): View
    {
        return view('crudHelper::elements.buttonGroups',[
            'button' => $this,
            'entity' => $entity
        ]);
    }
}
