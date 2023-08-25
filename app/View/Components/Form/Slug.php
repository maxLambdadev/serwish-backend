<?php

namespace App\View\Components\Form;

class Slug extends Input
{
    protected $slugClass = 'slug-input';

    public function __construct(?string $name, ?string $title = '', ?object $entity = null, ?string $entityKey = null, ?string $placeholder = null, string $type = 'text', ?string $group = null, $languageSwitcher = false, string $extraClasses = '')
    {
        parent::__construct($name, $title, $entity, $entityKey, $placeholder, $type, $group, $languageSwitcher, $this->slugClass,true);
    }

}
