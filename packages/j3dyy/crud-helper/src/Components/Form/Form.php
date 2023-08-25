<?php
namespace J3dyy\CrudHelper\Components\Form;

use Illuminate\View\View;
use J3dyy\CrudHelper\Components\Component;
use J3dyy\CrudHelper\Elements\Element;
use J3dyy\CrudHelper\Tools\Collectable;

class Form extends Component
{
    public  $action;
    public  $method;
    public  $elements;
    public  $actionButtons;


    public function __construct(string $action = '', string $method = 'GET', string $classes = null, string $id = null)
    {
        parent::__construct($classes,$id);
        $this->action = $action;
        $this->method = $method;
        $this->elements = collect([]);
        $this->actionButtons = collect([]);
    }

    public function addElements(Collectable $elements){
        $this->elements = $elements;
    }

    public function addElement(Element  $element): Form{
        $this->elements[] = $element;
        return $this;
    }

    public function addActionButtons(array $actionButtons): self{
        $this->actionButtons = $actionButtons;
        return $this;
    }

    function render(): View
    {
        return view('crudHelper::form.form',['form'=>$this]);
    }
}
