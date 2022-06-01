<?php

namespace App\View\Components\Department\Projects;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Show extends Component
{
    public $department;
    public $project;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($project, $department)
    {
        $this->department = $department;
        $this->project = $project;
    }

    public function getGroupByID($id)
    {
        $group = head(DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_groupe WHERE groupe_id='" . $id . "'"));
        if(empty($group))
        {
            return null;
        }
        else{
            return $group;
        }
    }

    public function getUserByID($id)
    {
        $user = head(DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE user_id='" . $id . "'"));
        if(empty($user))
        {
            return null;
        }
        else{
            return $user;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|Htmlable|View
     */
    public function render()
    {
        return view('components.department.projects.show');
    }
}
