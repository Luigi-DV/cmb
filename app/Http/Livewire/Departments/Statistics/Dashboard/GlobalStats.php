<?php

namespace App\Http\Livewire\Departments\Statistics\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GlobalStats extends Component
{
    public $departments, $department, $totalActiveUsers, $totalActiveUsersLastWeek, $weekOccupation, $lastWeekOccupation;
    public function render()
    {
        //Users
        $this->getNumberUsers();
        $this->getLastWeekNumberUsers();
        //Occupation
        $this->getWeekOccupation();
        $this->getLastWeekOccupation();

        return view('livewire.departments.statistics.dashboard.global-stats');
    }

    public function mount($department)
    {
        $this->departments = ['emc', 'clima', 'nvh'];
        $this->totalActiveUsers = 0;
        $this->totalActiveUsersLastWeek = 0;
        $this->weekOccupation = 0;
        $this->department = $department;
    }

    /**
     * Getting this week in course logged users
     */
    public function getNumberUsers() : void
    {
        $start = Carbon::now()->subWeek();
        $end = Carbon::now();
        $value = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE password is not null AND date_dernier_login BETWEEN '".$start."' AND '".$end."'");

        $this->totalActiveUsers = count($value);
    }

    /**
     * Getting the last week logged users
     */
    public function getLastWeekNumberUsers() : void
    {
        $start = Carbon::now()->subWeeks(2);
        $end = Carbon::now()->subWeek();
        $value = DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE password is not null AND date_dernier_login BETWEEN '".$start."' AND '".$end."'");

        $this->totalActiveUsersLastWeek = count($value);
    }

    public function getWeekOccupation(): void
    {
        $date = Carbon::now();
        $this->weekOccupation = $this->extractedGetOccupation($date);
    }

    public function getLastWeekOccupation():void
    {
        $date = Carbon::now()->subWeeks(2);
        $this->lastWeekOccupation = $this->extractedGetOccupation($date);
    }

    /**
     * Get Rooms
     * @param $search
     * @return array
     */
    public function getRooms(): array
    {
        if($this->department == 'emc')
        {
            $data = $this->getEmcRooms();
        }
        else{
            $data = $this->getOtherRooms();
        }
        return $data;

    }

    /**
     * @param $search
     * @return array
     */
    private function getOtherRooms(): array
    {
        return DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user  WHERE login IS NULL AND user_groupe_id IS NOT NULL");

    }


    /**
     * @param $search
     * @return array
     */
    private function getEmcRooms()
    {
        return DB::connection('mysql_'.$this->department)
            ->select("SELECT * FROM planning_user WHERE user_id regexp '^[0-9]' AND login IS NULL AND user_groupe_id IS NOT NULL");

    }

    /**
     * Compares Both data and return the percentage
     * @param $oldValue
     * @param $newValue
     * @return int
     */
    public function compareValue($oldValue, $newValue) : int
    {
        $difference =  $newValue - $oldValue;
        if($oldValue > 0){
            return (($difference / $oldValue) * 100);
        }
        else{
            return $difference * 100;
        }

    }

    /**
     * @param Carbon $date
     */
    private function extractedGetOccupation(Carbon $date): float
    {
        //Defining the start date
        $start = $date->startOfWeek()->format('Y-m-d');
        //Defining the end date
        $end = $date->endOfWeek()->format('Y-m-d');
        //Total Percent definition (used in iteration)
        $totalPercent = 0;

        //Iteration in Department Rooms
        foreach($this->getRooms() as $room)
        {
            //Getting the all the room tasks
            $tasks = DB::connection('mysql_' . $this->department)
                ->select("SELECT * FROM planning_periode WHERE projet_id regexp '^[0-9]' AND user_id = '".$room->user_id ."' AND date_debut BETWEEN '".$start."' AND '".$end."'");
            //Counting the tasks of single resource
            $result = count($tasks);
            //Maximum in week [15 Tasks]
            $value = 3 * 5;
            //Percentage
            $percentage = (($result *100) / ($value));
            $totalPercent = $totalPercent + $percentage;
        }
        $total = ($totalPercent/(count($this->getRooms())));
        //Returning the rounded value
        return round($total, 2);
    }
}
