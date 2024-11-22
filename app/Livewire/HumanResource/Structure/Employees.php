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
        // Searching using employee_id and first_name, last_name
        $employees = Employee::where('employee_id', 'like', '%' . $this->searchTerm . '%')
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
'employeeInfo.email' => 'required|email|unique:employees,email,' . ($this->isEdit ? $this->employeeInfo['employee_id'] : 'NULL') . ',employee_id',

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
        $this->employeeInfo = [
            'gender' => 'other',  // Default value
            'employment_status' => 'regular',  // Default value for employment status
            'account_type' => 'employee',  // Default value for account type
            // Set other fields as needed
        ];
    }

    protected function generateUniqueEmployeeId()
    {
        do {
            $randomId = 'EMP' . strtoupper(bin2hex(random_bytes(3)));
            \Log::info("Generated employee ID: " . $randomId); // Log the generated ID
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
    
            \Log::info('Adding employee with ID: ' . $this->employeeInfo['employee_id']); // Log the ID being added
    
            // Create the employee
            $employee = Employee::create($this->employeeInfo);
    
            // Only create the user if employee creation was successful
            if ($employee) {
                // Create the associated User
                $user = User::create([
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'employee_id' => $employee->employee_id,
                    'email' => $employee->email,
                    'password' => bcrypt('default_password'),
                    'account_type' => 'employee', // Adjust account type as needed
                ]);
    
                // Log the created user
                \Log::info('User created with ID: ' . $user->id . ' for Employee ID: ' . $employee->employee_id);
    
                // Determine the role_id (1 for admin, 2 for employee, etc.)
                // You may replace this logic with your actual role assignment mechanism
                $roleId = ($employee->account_type === 'admin') ? 1 : 2;

    
                // Insert the data into the model_has_roles table
                \DB::table('model_has_roles')->insert([
                    'role_id' => $roleId, // Assign the correct role ID
                    'model_type' => 'App\\Models\\User', // The model type
                    'model_id' => $user->id, // The user ID
                ]);
    
                \Log::info('Role assignment inserted for User ID: ' . $user->id . ' with Role ID: ' . $roleId);
            }
    
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

    // Initialize employeeInfo with all the fields that should be editable
    $this->employeeInfo = $employee->only([
        'employee_id', 'contract_id', 'first_name', 'last_name', 'gender',
        'email', 'mobile_number', 'birthday', 'nationality',
        'address', 'emergency_contact_name', 'emergency_contact_number',
        'relation_to_employee', 'emergency_contact_address', 'account_type',
        'employment_status', // Add this field if it's missing
        'position', // Add this field if it's missing
        'department' // Add this field if it's missing
    ]);

    // Optionally, ensure `employee_id` is set for edit mode
    if (!$this->employeeInfo['employee_id']) {
        // Handle case where employee_id is not found
        // This should not happen if the employee data is correct
        throw new \Exception("Employee ID is missing.");
    }
}

    

    public function editEmployee()
    {
        $employee = Employee::find($this->employeeInfo['employee_id']); // Search by employee_id
        $employee->update($this->employeeInfo);
        $this->dispatch('closeModal', elementId: '#employeeModal');
        $this->dispatch('toastr', type: 'success', message: __('Employee updated successfully!'));
        $this->reset('employeeInfo');
    }

    public function confirmDeleteEmployee($employeeId)
    {
        $this->confirmedId = $employeeId;
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
