<?php

namespace App\View\Components\Header\Breadcrumbs;

use Illuminate\View\Component;

class Edit extends Component
{
    public $title, $resources;
    public $id;
    public $management;
    public $scheduler;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $resources, $id, $management, $scheduler)
    {
        $this->title = $title;
        $this->resources = $resources;
        $this->id= $id;
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
        return view('components.header.breadcrumbs.edit');
    }
}
