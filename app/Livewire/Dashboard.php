<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $accountBalance = ['status' => 400, 'balance' => '---', 'is_active' => '---'];
    public $messagesStatus = ['sent' => 0, 'unsent' => 0];
    public $confirmedId;
    public $leaveRecords = [];
    public $userRole;
    public $selectedEmployeeId;

    public function mount()
    {
        // Get the authenticated user's employee data
        $user = Auth::user();

        if ($user) {
            $employee = Employee::find($user->employee_id);
            $this->selectedEmployeeId = $user->employee_id;

            // Check the role and assign it to the variable
            $this->userRole = $employee->role->name ?? 'guest'; // Default to 'guest' if no role
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'userRole' => $this->userRole,
        ]);
    }

    public function confirmDestroyLeave($id)
    {
        $this->confirmedId = $id;
    }

    public function destroyLeave()
    {
        EmployeeLeave::find($this->confirmedId)->delete();
        $this->dispatch('toastr', type: 'success', message: __('Going Well!'));
        $this->confirmedId = null;
    }

    public function getEmployeeName($id)
    {
        return Employee::find($id)->FullName;
    }

    public function getLeaveType($id)
    {
        return Leave::find($id)->name;
    }
}
