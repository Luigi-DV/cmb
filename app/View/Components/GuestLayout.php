<?php

namespace App\View\Components;
use Illuminate\Contracts\View\View;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
