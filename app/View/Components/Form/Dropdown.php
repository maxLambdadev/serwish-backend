<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Dropdown extends Component
{

    protected array $list;
    protected ?object $entity;
    protected ?string $title;
    protected ?string $name;
    protected string $value;
    protected string $displayName;
    protected ?string $defaultValue;
    protected ?string $related;
    protected ?bool $tags;

    public function __construct($list, string $value, string $displayName, ?object $entity = null, ?string $title = null, ?string $name = null,  ?string $defaultValue = null, ?string $related = null,?bool $tags = false)
    {
        $this->list = $list;
        $this->entity = $entity;
        $this->title = $title;
        $this->name = $name;
        $this->value = $value;
        $this->displayName = $displayName;
        $this->defaultValue = $defaultValue;
        $this->related = $related;
        $this->tags = $tags;
    }


    public function render()
    {
        return view('manager.partials.components.form.dropdown',[
            'list'          =>  $this->list,
            'entity'        =>  $this->entity,
            'title'         =>  $this->title,
            'name'          =>  $this->name,
            'value'         =>  $this->value,
            'displayName'   =>  $this->displayName,
            'defaultValue'  =>  $this->defaultValue,
            'related'     => $this->related,
            'tags'          => $this->tags
        ]);
    }
}
