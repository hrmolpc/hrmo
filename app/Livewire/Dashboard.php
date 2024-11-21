<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component


{
    public $requestTypeCounter = [];
    public $employmentStatusCount = [];
    public $inactiveEmploymentStatusCount = [];
    public $requests = [];
    public $userRole;
    

    public function mount()
    {
        $user = Auth::user();

        
   
        $this->countEmploymentStatus();

        $this->requestTypeCount();
        $this->countInactiveEmploymentStatus();
        $this->getAllRequests(); // Fetch a
    }

    public function countEmploymentStatus()
    {
        $this->employmentStatusCount = Employee::query()
            ->where('is_active', 1) // Only include active employees
            ->select('employment_status', \DB::raw('COUNT(*) as count'))
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status')
            ->toArray();
    }

    public function countInactiveEmploymentStatus()
    {
        $this->inactiveEmploymentStatusCount = Employee::query()
            ->where('is_active', 0) // Only include active employees
            ->select('employment_status', \DB::raw('COUNT(*) as count'))
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status')
            ->toArray();
    }
    

    public function requestTypeCount()
    {
        $this->requestTypeCounter = Request::query()
            ->select('type', \DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }
    public function getAllRequests()
    {
        // Set the date to 2024-11-17 (testing with a past date)
        $dateToFilter = Carbon::createFromFormat('Y-m-d', '2024-11-17', 'Asia/Manila');
    
        // Get the start and end of that day in Asia/Manila timezone
        $startOfDay = $dateToFilter->startOfDay();
        $endOfDay = $dateToFilter->endOfDay();
    
        // Log the values to see what the start and end of the day look like
       // dd($startOfDay, $endOfDay);
    
        // Fetch requests created on 2024-11-17 using the range
        $this->requests = Request::query()
            //->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();
    }

    public function getEmployeeName($employeeId)
    {
 
        $employee = \App\Models\Employee::find($employeeId);
    
        // Log whether the employee was found or not
        if ($employee) {
            // Combine first name and last name to create full name
            $fullName = $employee->first_name . ' ' . $employee->last_name;
           
            return $fullName;
        } else {
            return 'Unknown Employee';
        }
    }
    
    

    public function render()
    {
        return view('livewire.dashboard', [
            'employmentStatusCount' => $this->employmentStatusCount,
            'userRole' => $this->userRole,
            'requestTypeCounter' => $this->requestTypeCounter,
            'inactiveEmploymentStatusCount' => $this->inactiveEmploymentStatusCount,
            'requests' => $this->requests,
        ]);
    }
}
