<?php

namespace App\Http\Livewire\Dates;

use Carbon\Carbon;
use Livewire\Component;

class DateRangePicker extends Component
{
    public $dateFrom;
    public $dateTo;


    public function mount()
    {
        $this->getDateInit();
    }

    public function getDateInit()
    {
        $this->dateFrom = Carbon::now()->subDays(7);
        $this->dateTo = Carbon::now();
        //Emit
        $this->emit('getDateFrom', $this->dateFrom);
        $this->emit('getDateTo', $this->dateTo);
    }

    public function getDateFrom($date)
    {
        $this->dateFrom = $date;
        $this->emit('getDateFrom', $date);
    }

    public function getDateTo($date)
    {
        $this->dateTo = $date;
        $this->emit('getDateTo', $date);
    }

    public function render()
    {
        return view('livewire.dates.date-range-picker');
    }
}
