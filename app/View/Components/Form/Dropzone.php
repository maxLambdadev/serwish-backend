<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Dropzone extends Component
{
    protected ?object $entity;
    protected ?string $removeEntity;
    protected ?string $removeRoute;
    protected ?string $title;
    protected ?string $name;
    protected bool $multiple = true;

    /**
     * @param object|null $entity
     * @param string|null $title
     * @param string|null $name
     */
    public function __construct(?object $entity, ?string $removeEntity, ?string $removeRoute, ?string $title, ?string $name, bool $multiple = true)
    {
        $this->entity = $entity;
        $this->removeEntity = $removeEntity;
        $this->removeRoute = $removeRoute;
        $this->title = $title;
        $this->name = $name;
        $this->multiple = $multiple;
    }


    public function render()
    {
        $entity = $this->entity;
        $name = $this->name;
        $title = $this->title;
        $removeEntity = $this->removeEntity;
        $removeRoute = $this->removeRoute;
        $multiple = $this->multiple;

        return view('manager.partials.components.form.dropzone',
            compact("entity","removeEntity", "removeRoute","title","name","multiple")
        );
    }
}
