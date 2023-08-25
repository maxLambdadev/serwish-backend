<?php

namespace J3dyy\CrudHelper\Elements;

use Illuminate\View\View;

abstract class Element
{
    public $type;
    public $key;
    public $content;

    public $classes = null;
    public $id = null;

    public $dataAttrs = [];
    public $label;

    /**
     * @param $type
     * @param $key
     * @param $content
     * @param null $classes
     * @param null $id
     */
    public function __construct($type, $key, $content, $classes = null, $id = null, array $dataAttrs = [])
    {
        if ($id == null) $id = $key;

        $this->type = $type;
        $this->key = $key;
        $this->content = $content;
        $this->label = $content;
        $this->classes = $classes;
        $this->id = $id;
        $this->dataAttrs = $dataAttrs;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;

    }

    /**
     * @param null $classes
     */
    public function setClasses($classes): self
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @param null $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): self
    {
        $this->label = $label;
        return $this;
    }


    /**
     * @param array $dataAttrs
     * @return Element
     */
    public function setDataAttrs(array $dataAttrs): self
    {
        $this->dataAttrs = $dataAttrs;
        return $this;
    }

    abstract function transform(array $entity): View;

}
