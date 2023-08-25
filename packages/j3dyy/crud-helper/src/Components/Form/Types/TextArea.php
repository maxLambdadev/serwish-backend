<?php

namespace J3dyy\CrudHelper\Components\Form\Types;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class TextArea extends Input
{
    public $validator = '';
    public $value = '';

    public function __construct($type = '', $key = '', $content = '', $classes = null, $id = null, array $dataAttrs = [])
    {
        $this->value = $content;
        parent::__construct($type, $key, $content, $classes, $id, $dataAttrs);
    }

    public static function text($key,$value, $classes = 'form-control'){
        return new Input(ElementTypes::TEXT, $key,$value, $classes);
    }

    public static function image($key, $value, $classes = 'form-control'){
        return new Input(ElementTypes::IMAGE, $key,$value,$classes);
    }

    public static function hidden($key,$value){
        return new Input(ElementTypes::HIDDEN, $key, $value);
    }


    function transform(array $entity): View
    {
        return view('crudHelper::form.textarea',[
            'textarea' => $this,
            'entity' => $entity
        ]);
    }
}
