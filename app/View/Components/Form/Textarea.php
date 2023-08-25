<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Textarea extends Component
{
    protected ?object $entity;
    protected ?string $entityKey;
    protected ?string $title;
    protected ?string $name;
    protected bool $languageSwitcher = false;

    /**
     * @param object|null $entity
     * @param string|null $entityKey
     * @param string|null $title
     * @param string|null $name
     * @param bool $languageSwitcher
     */
    public function __construct(?object $entity = null, ?string $entityKey = null, ?string $title = '', ?string $name = '', bool $languageSwitcher = false)
    {
        $this->entity = $entity;
        $this->entityKey = $entityKey;
        $this->title = $title;
        $this->name = $name;
        $this->languageSwitcher = $languageSwitcher;
    }


    public function render()
    {
        $entity = $this->entity;
        $entityKey = $this->entityKey;
        $name = $this->name;
        $title = $this->title;
        $language = $this->languageSwitcher;

        return view('manager.partials.components.form.textarea',
            compact("entity", "entityKey","title","name","language")
        );
    }
}
