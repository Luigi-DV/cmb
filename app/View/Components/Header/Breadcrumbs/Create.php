<?php

namespace App\View\Components\Header\Breadcrumbs;

use Illuminate\View\Component;

class Create extends Component
{
    public $resources;
    public $management;
    public $scheduler;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($resources, $management, $scheduler)
    {
        $this->resources = $resources;

        //Management
        $this->management = $management;
        $this->scheduler = $scheduler;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header.breadcrumbs.create' );
    }
}
