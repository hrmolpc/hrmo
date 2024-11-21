<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Request;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    // Shared properties for both admin and employee
    public $requestTypeCounter = [];
    public $employmentStatusCount = [];
    public $inactiveEmploymentStatusCount = [];
    public $requests = [];
    public $userRole;
    public $requestType;
    public $requestDetails;
    public $dateFrom;
    public $dateTo;
    public $attachment;
    public $requestToDelete; // Store the ID of the request to be deleted

    // Validation rules for request submission
    protected $rules = [
        'requestType' => 'required|string',
        'requestDetails' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,jpeg,png',
        'dateFrom' => 'nullable|date',
        'dateTo' => 'nullable|date|after_or_equal:dateFrom',
    ];

    // Mount function to load data and check user role
    public function mount()
    {
        $user = Auth::user();

        // Get the user's account type
        $accountType = $user->account_type;

        // Assign the account type to the component's public property
        $this->userRole = $accountType;

        // Call functions based on the user role
        $this->countEmploymentStatus();
        $this->requestTypeCount();
        $this->countInactiveEmploymentStatus();
        $this->getAllRequests(); // Fetch all requests
    }

    // Count employment status for active employees
    public function countEmploymentStatus()
    {
        $this->employmentStatusCount = Employee::query()
            ->where('is_active', 1) // Only include active employees
            ->select('employment_status', \DB::raw('COUNT(*) as count'))
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status')
            ->toArray();
    }

    // Count employment status for inactive employees
    public function countInactiveEmploymentStatus()
    {
        $this->inactiveEmploymentStatusCount = Employee::query()
            ->where('is_active', 0) // Only include inactive employees
            ->select('employment_status', \DB::raw('COUNT(*) as count'))
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status')
            ->toArray();
    }

    // Count request types
    public function requestTypeCount()
    {
        $this->requestTypeCounter = Request::query()
            ->select('type', \DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    // Fetch all requests created on a specific date (e.g., 2024-11-17)
    public function getAllRequests()
    {
        $dateToFilter = Carbon::createFromFormat('Y-m-d', '2024-11-17', 'Asia/Manila');
        $startOfDay = $dateToFilter->startOfDay();
        $endOfDay = $dateToFilter->endOfDay();

        $this->requests = Request::query()
            //->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();
    }

    // Get the full name of an employee by ID
    public function getEmployeeName($employeeId)
    {
        $employee = Employee::find($employeeId);

        if ($employee) {
            return $employee->first_name . ' ' . $employee->last_name;
        }

        return 'Unknown Employee';
    }

    // Save a new request (for employee)
    public function saveRequest()
    {
        $this->validate();

        $data = [
            'employee_id' => auth()->user()->id,
            'type' => $this->requestType,
            'description' => $this->requestDetails,
            'status' => 'Pending',
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'is_active' => true,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'deleted_by' => null,
            'requestor_attachment' => $this->uploadAttachment(),
            'approver_attachment' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];

        Request::create($data);

        session()->flash('message', 'Request submitted successfully!');
        $this->reset(); // Reset the form fields after submission
    }

    // Set the request to be deleted
    public function setRequestToDelete($requestId)
    {
        $this->requestToDelete = $requestId;
    }

    // Delete the request
    public function deleteRequest()
    {
        try {
            $request = Request::findOrFail($this->requestToDelete);
            $request->delete();

            session()->flash('message', 'Request deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete request.');
        }
    }

    // Handle file upload for attachments
    public function uploadAttachment()
    {
        if ($this->attachment) {
            try {
                $path = $this->attachment->store('attachments', 'public');
                return $path;
            } catch (\Exception $e) {
                session()->flash('error', 'Error uploading file: ' . $e->getMessage());
            }
        }

        return null;
    }

    // Render the appropriate view
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
