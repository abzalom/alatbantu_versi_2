<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class AppSidebarComponent extends Component
{
    /**
     * Create a new component instance.
     */

    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menus = json_decode(Storage::disk('public')->get('app/menus.json'), true);
        $sidebarHeaderName = "";
        $sidebarHeaderSubName = "";
        foreach ($menus as $menu) {
            if (request()->is($menu['current']) || request()->is($menu['current'] . '/*')) {
                $sidebarHeaderName = $menu['name'];
                if (isset($menu['subs'])) {
                    foreach ($menu['subs'] as $sub) {
                        if (request()->is($sub['current']) || request()->is($sub['current'] . '/*')) {
                            $sidebarHeaderSubName = $sub['name'];
                        }
                    }
                }
            }
        }
        return view('components.app-sidebar-component', [
            'menus' => $menus,
            'sidebarHeaderName' => $sidebarHeaderName ? $sidebarHeaderName : 'Header',
            'sidebarHeaderSubName' => $sidebarHeaderSubName ? $sidebarHeaderSubName : 'Sub Header',
        ]);
    }
}
