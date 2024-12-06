<?php

namespace App\View\Components;

use App\Models\StockItemCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategoryItemSelect extends Component
{

    public $categories;
    public $selectedCategory;

    /**
     * Create a new component instance.
     */
    public function __construct($selectedCategory = null)
    {
        $this->categories = StockItemCategory::all();
        $this->selectedCategory = $selectedCategory;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category-item-select');
    }
}
