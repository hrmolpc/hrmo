<?php
namespace App\Livewire\HumanResource\Structure;

use App\Models\Employee;
use App\Models\EmployeeInformationHistory;
use Livewire\Component;

class EmployeeInfo extends Component
{
    public $employee;
    public $history = []; // Add history variable

    public function mount($employee_id)
    {
        $this->employee = Employee::where('employee_id', $employee_id)->first(); // Load by employee_id

        // Load employee information history if employee is found
        if ($this->employee) {
            $this->history = EmployeeInformationHistory::where('employee_id', $employee_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.human-resource.structure.employee-info', [
            'employee' => $this->employee,
            'history' => $this->history,
        ]);
    }
}
