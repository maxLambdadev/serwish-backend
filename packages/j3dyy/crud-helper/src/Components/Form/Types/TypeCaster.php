<?php

namespace J3dyy\CrudHelper\Components\Form\Types;

use J3dyy\CrudHelper\Elements\Element;

trait TypeCaster
{
    protected static $types = [
        'text'          =>  TextArea::class,
        'string'        =>  Input::class,
        'boolean'       =>  Checkbox::class,
        'bigint'       =>  Checkbox::class,
    ];

    public static function castTo(string $type): ?Input
    {
        $tp = array_key_exists($type, self::$types) ? self::$types[$type] : null ;

        if ($tp != null){
            return new $tp();
        }

        return null;
    }
}
