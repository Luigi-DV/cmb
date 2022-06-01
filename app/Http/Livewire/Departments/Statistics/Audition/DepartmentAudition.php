<?php

namespace App\Http\Livewire\Departments\Statistics\Audition;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepartmentAudition extends Component
{
    public $department, $departments, $audition;
    public $start, $end;
    public $perPage;
    public $showLoading;

    public $dateFrom;
    public $dateTo;

    protected $listeners = [
        'getDateFrom',
        'getDateTo',
        'load-more' => 'loadMore',
    ];

    public function render()
    {
        $this->getDepartmentAudition();
        $this->checkLoadingMore();
        return view('livewire.departments.statistics.audition.department-audition');
    }

    public function mount($department)
    {
        $this->departments = ['emc', 'clima', 'nvh'];
        $this->audition = [];
        $this->start = Carbon::now()->subDays(7);
        $this->end = Carbon::now();
        $this->perPage = 10;
        $this->showLoading = false;
        $this->department = $department;
    }


    public function checkLoadingMore()
    {
        if($this->audition){
            if(count($this->audition) >= $this->perPage) {
                $this->showLoading = true;
            }
        }
    }

    public function loadMore()
    {
        if($this->audition){
            if(count($this->audition) >= $this->perPage)
            {
                $this->perPage = $this->perPage + 10;
            }
            else{
                $this->showLoading = false;
            }
        }
    }

    public function getProjectById($id)
    {
        $value = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_projet WHERE projet_id='".$id."'");

        return $value[0];
    }

    /**
     * From Listener
     * @param $date
     */
    public function getDateFrom($date)
    {
        $this->start = new Carbon($date);
    }

    /**
     * From Listener
     * @param $date
     */
    public function getDateTo($date)
    {
        $this->end = new Carbon($date);
    }


    public function getDepartmentAudition()
    {
        $this->audition = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_audit WHERE
                                   date_modif BETWEEN '".$this->start."' AND '".$this->end."' ORDER BY date_modif DESC");
    }


    public function getUserById($id)
    {
        $value = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE user_id='".$id."'");

        return $value[0];
    }
    public function getUserByLieu($lieu)
    {
        $value = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE user_id='".$lieu."'");
        return $value;

    }

}


