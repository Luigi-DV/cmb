<?php

namespace App\Http\Livewire\Departments\Statistics\Resources;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Occupation extends Component
{
    use WithPagination;

    public $department = null;

    public $departments = ['emc','nvh','clima'];
    public $results = 5;
    public $order = 'ASC';

    public $dateFrom;
    public $dateTo;
    public $period;

    public $search = '';

    protected $listeners = ['getDateFrom', 'getDateTo'];

    public function mount($department)
    {
        //Parameter
        $this->department = $department;
        //Constructor
        $this->dateFrom = Carbon::now();
        $this->dateTo = Carbon::now()->addDays(5);
        $this->period =  CarbonPeriod::create($this->dateFrom, $this->dateTo)->toArray();
    }

    public function getRoomOccupations($id, $date)
    {
        $start = $date->startOfWeek()->format('Y-m-d');
        $end = $date->endOfWeek()->format('Y-m-d');

        $tasks = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_periode WHERE projet_id regexp '^[0-9]' AND user_id = '".$id ."' AND date_debut BETWEEN '".$start."' AND '".$end."'");
        //Counting the tasks
        $result = count($tasks);
        //Maximum in week [15 Tasks]
        $value = 3 * 5;
        //Percentage
        $return = (($result *100) / ($value));
        //Rounding the result
        return round($return, 0);

    }


    public function getRoomHours($id, $date)
    {
        $start = $date->startOfWeek()->format('Y-m-d');
        $end = $date->endOfWeek()->format('Y-m-d');

        $occupation = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_periode WHERE projet_id regexp '^[0-9]' AND user_id = ".$id ." AND date_debut BETWEEN '".$start."' AND '".$end."'");
        $value = 0;
        foreach($occupation as $occ){
            $value = $value + (int) $occ->duree;
        }

        return print_r($value);
    }

    /**
     * @param $date
     * @return Carbon|int
     */
    public function getWeek($date)
    {
        $carbon =  Carbon::parse($date);
        return $carbon->isoWeek();
    }

    /**
     * @return mixed
     */
    public function getWeeks()
    {
        return ($this->dateFrom)->diffInWeeks($this->dateTo);
    }

    /**
     * @param $from
     * @param $to
     * @return CarbonPeriod
     */
    public function getPeriod($from, $to)
    {
        $period = CarbonPeriod::create($from, $to);
        $this->period = $period->toArray();
        return $this->period;
    }

    /**
     * From Listener
     * @param $date
     */
    public function getDateFrom($date)
    {
        $this->dateFrom = new Carbon($date);
    }

    /**
     * From Listener
     * @param $date
     */
    public function getDateTo($date)
    {
        $this->dateTo = new Carbon($date);
    }

    /**
     * Get Rooms
     * @param $search
     * @return array
     */
    public function getRooms($search): array
    {
        if($this->department == 'emc')
        {
            $data = $this->getEmcRooms($search);
        }
        else{
            $data = $this->getOtherRooms($search);
        }
        return $data;

    }

    /**
     * @param $search
     * @return array
     */
    private function getOtherRooms($search): array
    {
        return DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user  WHERE nom LIKE '" . $search . "' AND login IS NULL AND user_groupe_id IS NOT NULL
            ORDER BY order_planning " . $this->order);

    }


    /**
     * @param $search
     * @return array
     */
    private function getEmcRooms($search)
    {
        return DB::connection('mysql_'.$this->department)
            ->select("SELECT * FROM planning_user WHERE user_id regexp '^[0-9]' AND nom LIKE '".$search."' AND login IS NULL AND user_groupe_id IS NOT NULL ORDER BY user_groupe_id ".$this->order);

    }

    /**
     * Render Function
     * @return Application|Factory|View
     */
    public function render()
    {
        $search = '%'.$this->search.'%';
        $rooms = $this->getRooms($search);
        return view('livewire.departments.statistics.resources.occupation', [
            'rooms' => $rooms,
            'departments' => $this->departments,
        ]);
    }
}
