<?php

namespace J3dyy\CrudHelper\Components\Form;

use Illuminate\Database\Eloquent\Model;
use J3dyy\CrudHelper\Components\Form\Types\Input;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Tools\Collectable;
use J3dyy\CrudHelper\Tools\ModelTools;

class FormBuilder
{
    protected $grid;
    protected $form;

    public function __construct(string $action = '', string $method = 'GET',$classes = null, string $id = null){
        $this->grid = new Collectable();
        $this->form = new Form($action, $method,$classes,$id);
    }

    public function fields(Model ...$model) {
        $fields = ModelTools::getFields(...$model);

        foreach ($fields as $type=>$items){
            foreach ($items as $name => $data){
                $input = Input::formLayout($data);
                $input != null  ? $this->add($type,$input) : '' ;
            }
        }

        $this->form->addElements($this->grid);
        return $this;
    }

    public function add($type, Element $element){
        $this->grid->get($type) != null ?: $this->grid->put($type, new Collectable());

        $this->grid->get($type)->put($element->key, $element);
    }

    private function reduce(string $position, callable $then){
        $this->grid->get($position) == null ?: $then();
    }

    private function castToInput(){
        $casted = null;
        return $casted;
    }

    public function build(){
        return $this->form;
    }


}
