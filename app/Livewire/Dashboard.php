<?php

namespace App\Livewire;

use App\Jobs\sendPendingMessages;
use App\Models\Center;
use App\Models\Changelog;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\Leave;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Livewire\Component;
use Throwable;

class Dashboard extends Component
{
    public $accountBalance = ['status' => 400, 'balance' => '---', 'is_active' => '---'];

    public $messagesStatus = ['sent' => 0, 'unsent' => 0];

    public $changelogs;

    public $activeEmployees;

    public $center;

    public $selectedEmployeeId;

    public $leaveTypes;

    public $employeeLeaveId;

    public $employeeLeaveRecord;

    public $isEdit = false;

    public $confirmedId;

    public $leaveRecords = [];

    public $newLeaveInfo = [
        'LeaveId' => '',
        'fromDate' => null,
        'toDate' => null,
        'startAt' => null,
        'endAt' => null,
        'note' => null,
    ];

    public $fromDateLimit;


    public function mount()
    {
        $user = Employee::find(Auth::user()->employee_id);
   
      

        $this->selectedEmployeeId = Auth::user()->employee_id;
    

 

   
 
    }

    public function render()
    {
     
      

      

        return view('livewire.dashboard');
    }

    public function updatedSelectedEmployeeId()
    {
        $employee = Employee::find($this->selectedEmployeeId);

    
            $this->reset('employeePhoto');
    
    }

 

 
    public function createLeave()
    {
   

 
    }

    public function showEditLeaveModal($id)
    {
  
    }

    public function updateLeave()
    {
 
    }

    public function submitLeave()
    {
   
    }

    public function confirmDestroyLeave($id)
    {
        $this->confirmedId = $id;
    }

    public function destroyLeave()
    {
        EmployeeLeave::find($this->confirmedId)->delete();

        $this->dispatch('toastr', type: 'success' /* , title: 'Done!' */, message: __('Going Well!'));
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
