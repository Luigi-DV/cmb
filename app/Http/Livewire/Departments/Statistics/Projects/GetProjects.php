<?php

namespace App\Http\Livewire\Departments\Statistics\Projects;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GetProjects extends Component
{
    public $searchProject = '';
    public $paymentIndicator;
    public $department;

    public function mount($department)
    {
        //Parameter
        $this->department = $department;
        $this->paymentIndicator = 'po';
    }

    public function render()
    {
        $search = $this->searchProject . '%';
        $projects = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_projet WHERE statut_bill='" . $this->paymentIndicator . "' AND nom LIKE '" . $search . "' ORDER BY projet_id");
        return view('livewire.departments.statistics.projects.get-projects', [
            'projects' => $projects,
        ]);
    }
}
