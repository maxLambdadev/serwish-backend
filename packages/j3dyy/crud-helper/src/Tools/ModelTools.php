<?php
namespace J3dyy\CrudHelper\Tools;


use Illuminate\Database\Eloquent\Model;

class ModelTools
{

    /**
     * @param Model ...$model
     * @return Collectable
     */
    public static function getFields(Model ...$model){
        $types = new Collectable();
        foreach ($model as $m){
            $hiddens = new Collectable($m->getHidden());
            foreach ($m->getFillable() as $fillable){
                $field = $m->getConnection()->getDoctrineColumn($m->getTable(),$fillable);
                //initialize type array
                $types->get($field->getType()->getName()) != null ?: $types->put($field->getType()->getName(),new Collectable());
                $types->get($field->getType()->getName())->put($fillable, [
                    'type'      =>  $field->getType()->getName(),
                    'length'    =>  $field->getLength(),
                    'nullable'  =>  $field->getNotnull(),
                    'default'   =>  $field->getDefault(),
                    'name'      =>  $field->getName(),
                    'model'     =>  $m->getTable(),
                    'hidden'    =>  is_int($hiddens->search($fillable)) == true
                ]);
            }
        }
        return  $types;
    }



    public static function castToElementType(string $type){
        dd($type);
    }
}
