<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Switcher extends Component
{
    protected ?object $entity;
    protected ?string $entityKey;
    protected ?string $title;
    protected ?string $name;

    /**
     * @param string|null $entity
     * @param string|null $title
     * @param string|null $name
     */
    public function __construct(?object $entity, ?string $title, ?string $name,  ?string $entityKey = null)
    {
        $this->entity = $entity;
        $this->title = $title;
        $this->name = $name;
        $this->entityKey = $entityKey;
    }


    public function render()
    {
        $entity = $this->entity;
        $entityKey = $this->entityKey;
        $name = $this->name;
        $title = $this->title;

        return view('manager.partials.components.form.switch',
            compact("entity", "entityKey","title","name")
        );
    }
}
