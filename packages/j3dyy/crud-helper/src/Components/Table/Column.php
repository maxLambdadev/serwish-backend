<?php

namespace J3dyy\CrudHelper\Components\Table;

use Illuminate\View\View;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Elements\ElementTypes;

class Column extends Element
{

    public function __construct($type, $key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        parent::__construct($type, $key, $content, $classes, $id, $dataAttrs);
    }


    public static function raw($key,$content, $classes = null){
        return new Column(ElementTypes::COLUMN,$key,$content, $classes);
    }

    public static function html($key,$label,$html, $classes = null){
        $htmlColumn = new Column(ElementTypes::HTML, $key,$html,$classes);
        $htmlColumn->setLabel($label);
        return $htmlColumn;
    }



    function transform(array $entity): View
    {
        return view('crudHelper::table.column',[
            'column'=>$this,
            'entity'=>$entity
        ]);
    }

}
