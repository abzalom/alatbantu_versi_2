<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayoutComponent extends Component
{

    public $title;
    public $desc;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "RAP-APP", $desc = "Deskrisi Halaman")
    {
        $this->title = $title;
        $this->desc = $desc;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.app-layout-component', [
            'app' => [
                'title' => $this->title,
                'desc' => $this->desc,
            ],
        ]);
    }
}
