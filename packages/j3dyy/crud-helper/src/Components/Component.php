<?php
namespace J3dyy\CrudHelper\Components;


use Illuminate\View\View;

abstract class  Component
{

    public $classes = null;
    public $id = null;

    /**
     * @param string|null $classes
     * @param string|null $id
     */
    public function __construct(string $classes = null, string $id = null)
    {
        $this->classes = $classes;
        $this->id = $id;
    }

    /**
     * @param string|null $classes
     */
    public function setClasses(?string $classes): self
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }



    abstract function render(): View;

}
