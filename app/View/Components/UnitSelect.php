<?php

namespace App\View\Components;

use Closure;
use App\Models\Unit;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class UnitSelect extends Component
{

    public $units;
    public $selectedUnit;

    /**
     * Create a new component instance.
     */
    public function __construct($selectedUnit = null)
    {
        $this->units = Unit::all();
        $this->selectedUnit = $selectedUnit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.unit-select');
    }
}
