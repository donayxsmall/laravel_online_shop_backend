<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextField extends Component
{
    public $label;
    public $name;
    public $value;
    public $readonly;
    public $formatNumber;
    public $required;
    /**
     * Create a new component instance.
     */
    public function __construct($label,$name,$value=null,$readonly=false,$formatNumber = false,$required=false)
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->readonly = $readonly;
        $this->formatNumber = $formatNumber;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.text-field');
    }
}
