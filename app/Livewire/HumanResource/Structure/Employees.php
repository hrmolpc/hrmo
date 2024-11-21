<?php

namespace App\Livewire\HumanResource\Structure;

use App\Models\Employee;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Employees extends Component
{
    use WithPagination;

    public $searchTerm = null;
    public $employeeInfo = [];
    public $isEdit = false;
    public $confirmedId;

    public function render()
    {
        $employees = Employee::where('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('first_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
            ->paginate(20);

        return view('livewire.human-resource.structure.employees', [
            'employees' => $employees,
        ]);
    }

    public function submitEmployee()
    {
        $this->validate([
            'employeeInfo.first_name' => 'required|string|max:255',
            'employeeInfo.last_name' => 'required|string|max:255',
            'employeeInfo.gender' => 'required|string',
            'employeeInfo.email' => 'required|email|unique:employees,email,' . ($this->isEdit ? $this->employeeInfo['id'] : 'NULL'),
            'employeeInfo.mobile_number' => 'required|string|max:15',
            'employeeInfo.birthday' => 'required|date',
            'employeeInfo.nationality' => 'required|string|max:100',
            'employeeInfo.address' => 'required|string|max:255',
            'employeeInfo.emergency_contact_name' => 'required|string|max:255',
            'employeeInfo.emergency_contact_number' => 'required|string|max:15',
            'employeeInfo.relation_to_employee' => 'required|string|max:50',
            'employeeInfo.emergency_contact_address' => 'required|string|max:255',
            'employeeInfo.account_type' => 'required|string|max:255',
        ]);

        $this->isEdit ? $this->editEmployee() : $this->addEmployee();
    }

    public function showCreateEmployeeModal()
    {
        $this->reset('isEdit', 'employeeInfo');
        $this->employeeInfo['gender'] = 'other'; // Default value for gender
    }

    protected function generateUniqueEmployeeId()
    {
        do {
            // Generate a random ID (customize as needed)
            $randomId = 'EMP' . strtoupper(bin2hex(random_bytes(3))); // Generates a string like EMP1A2B3
        } while (Employee::where('employee_id', $randomId)->exists());
    
        return $randomId;
    }
    

    public function addEmployee()
    {
        try {
            // Generate employee_id if not editing an existing record
            if (!$this->isEdit) {
                $this->employeeInfo['employee_id'] = $this->generateUniqueEmployeeId();
            }
            
    
            // Create the employee record
            Employee::create($this->employeeInfo);
            
            $this->dispatch('closeModal', elementId: '#employeeModal');
            $this->dispatch('toastr', type: 'success', message: __('Employee added successfully!'));
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: __('Failed to add employee: ' . $e->getMessage()));
        } finally {
            $this->reset(['employeeInfo']);
        }
    }
    

    public function showEditEmployeeModal(Employee $employee)
    {
        $this->isEdit = true;
        $this->employeeInfo = $employee->only([
            'id', 'contract_id', 'first_name', 'last_name', 'gender',
            'email', 'mobile_number', 'birthday', 'nationality',
            'address', 'emergency_contact_name', 'emergency_contact_number',
            'relation_to_employee', 'emergency_contact_address', 'account_type'
        ]);
    }

    public function editEmployee()
    {
        $employee = Employee::find($this->employeeInfo['id']);
        $employee->update($this->employeeInfo);
        $this->dispatch('closeModal', elementId: '#employeeModal');
        $this->dispatch('toastr', type: 'success', message: __('Employee updated successfully!'));
        $this->reset('employeeInfo');
    }

    public function confirmDeleteEmployee($id)
    {
        $this->confirmedId = $id;
    }

    public function deleteEmployee()
    {
        $employee = Employee::find($this->confirmedId);
        if ($employee) {
            $employee->delete();
            $this->dispatch('toastr', type: 'success', message: __('Employee deleted successfully!'));
        }
        $this->confirmedId = null; // Reset after deletion
    }
}
