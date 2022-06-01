<?php

namespace App\Http\Livewire\Departments\Statistics\Forms;

use Livewire\Component;

class ChangeDepartment extends Component
{
    public $departments = ['emc','nvh','clima'];
    public $department;
    public $url;

    public function mount($url)
    {
        $this->fill(['department' =>  session()->get('department')]);
        $this->url = $url;
    }

    public function changeDepartment()
    {
        session()->put('department', $this->department);
        return redirect()->to($this->url);
    }

    public function render()
    {
        return view('livewire.departments.statistics.forms.change-department');
    }
}
