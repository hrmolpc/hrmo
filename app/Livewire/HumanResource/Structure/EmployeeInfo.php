<?php

namespace App\Livewire\HumanResource\Structure;

use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeInfo extends Component
{
    public $employee;
    public $isEdit = false;


    // Mount method to load employee information
    public function mount($employee_id)
    {
        $this->employee = Employee::find($employee_id);
        // You can load other related data if needed
    }

    // Render method
    public function render()
    {
        return view('livewire.human-resource.structure.employee-info');
    }

    // Toggle active status of the employee
    public function toggleActive()
    {
        $this->employee->is_active = !$this->employee->is_active;

        $this->employee->save();
        $this->dispatch('toastr', type: 'success', message: __('Employee status updated successfully!'));
    }



}
