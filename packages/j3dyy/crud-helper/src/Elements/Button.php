<?php

namespace J3dyy\CrudHelper\Elements;

use Illuminate\View\View;

class Button extends Element
{
    public $link = null;

    public function __construct($key, $content, $classes = null, $id = null, array $dataAttrs = [], string $link = null, string $type = ElementTypes::BUTTON)
    {
        parent::__construct($type, $key, $content, $classes, $id, $dataAttrs);
        $this->link = $link;
    }


    public function asLink($url)
    {
        $this->link = $url;
        return $this;
    }

    public function transform(array $entity): View
    {
        return view('crudHelper::elements.button',[
            'button' => $this,
            'entity' => $entity
        ]);
    }

    public static function linkButton($key,$content,$link){
        return new Button($key,$content,null, null, [], $link );
    }

    public static function standard($key,$content){
        return new Button($key,$content);
    }
}
