<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownField extends Component
{
    public $label;
    public $name;
    public $items;
    public $selected;
    public $required;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $name, $items, $selected = null,$required=false)
    {
        $this->label = $label;
        $this->name = $name;
        $this->items = $items;
        $this->selected = $selected;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.dropdown-field');
    }
}
