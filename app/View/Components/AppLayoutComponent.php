<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayoutComponent extends Component
{

    public $title;
    public $desc;
    public $app;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "RAP-APP", $desc = "Deskrisi Halaman", $app = [])
    {
        $this->title = $title;
        $this->desc = $desc;
        $this->app = $app;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // dump($this->app);
        return view('components.app-layout-component', [
            'app' => [
                'title' => $this->title,
                'desc' => $this->desc,
            ],
        ]);
    }
}
