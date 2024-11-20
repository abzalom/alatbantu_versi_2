<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class AppNavbarComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $navbar_menus = json_decode(Storage::disk('public')->get('app/menus.json'), true);
        return view('components.app-navbar-component', [
            'navbar_menus' => $navbar_menus,
        ]);
    }
}
