<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    protected ?object $entity;
    protected ?string $entityKey;
    protected ?string $title;
    protected ?string $name;
    protected ?string $placeholder;
    protected string $type;
    protected ?string $group;
    protected bool $languageSwitcher = false;
    protected ?string $extraClasses = '';
    protected bool $multiLangClass = false;

    public function __construct(?string $name, ?string $title = '', ?object $entity = null, ?string $entityKey = null, ?string $placeholder = null, string $type = 'text', ?string $group = null, $languageSwitcher = false, string $extraClasses = '', $multiLangClass = false)
    {
        $this->entity = $entity;
        $this->entityKey = $entityKey;
        $this->title = $title;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->group = $group;
        $this->languageSwitcher = $languageSwitcher;
        $this->extraClasses = $extraClasses;
        $this->multiLangClass = $multiLangClass;
    }

    public function render()
    {
        $entity = $this->entity;
        $entityKey = $this->entityKey;
        $name = $this->name;
        $title = $this->title;
        $placeholder = $this->placeholder;
        $type = $this->type;
        $group = $this->group;
        $language = $this->languageSwitcher;
        $extraClasses = $this->extraClasses;
        $multiLangClass = $this->multiLangClass;

        return view('manager.partials.components.form.input',
            compact("entity", "entityKey","title","name","placeholder","type","group","language","extraClasses","multiLangClass")
        );
    }
}
