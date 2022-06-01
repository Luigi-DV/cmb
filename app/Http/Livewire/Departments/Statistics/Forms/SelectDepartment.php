<?php

namespace App\Http\Livewire\Departments\Statistics\Forms;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class SelectDepartment extends Component
{
    public $department = null;
    public $departments = ['emc', 'nvh', 'clima'];
    public $url;

    public function mount($url)
    {
        $this->department = null;
        $this->url = $url;
    }

    protected $rules = [
        'department' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetSearch()
    {
        $this->reset('department');
    }

    public function setValue($value)
    {
        $this->fill(['department' => $this->departments[$value]]);
    }

    public function submitDepartment()
    {
        $validatedData = $this->validate();
        session()->put('department', $validatedData['department']);
        return redirect()->to($this->url);

    }

    public function render()
    {
        return view('livewire.departments.statistics.forms.select-department', [
            'departments' => $this->departments
        ]);
    }
}
