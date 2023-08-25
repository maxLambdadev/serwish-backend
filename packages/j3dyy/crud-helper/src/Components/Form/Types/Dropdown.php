<?php

namespace J3dyy\CrudHelper\Components\Form\Types;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class Dropdown extends Input
{
    public $options = [];

    public function __construct($key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct(ElementTypes::SELECT, $key, $content, $classes, $id, $dataAttrs);
    }

    public static function of($key,$classname = 'form-control'): self{
        return new Dropdown($key,'',$classname);
    }

    public function addOptions(array $options): self{
        $this->options = $options;
        return $this;
    }

    public function addOption(Option $option): self{
        $this->options[] = $option;
        return $this;
    }

    function transform(array $entity): View
    {
        //todo
        return view('administration');
    }
}
