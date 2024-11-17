<?php
namespace App\Livewire\HumanResource;

use Livewire\Component;
use App\Models\Request; // Import Request model
use Livewire\WithPagination;

class COE extends Component
{
    use WithPagination;

    public $confirmedId = null; // To track which request is being deleted
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status
    public $searchTerm = ''; // For search by employee name

    public function mount()
    {
        // Initialize if needed
    }

    // Function to get employee name by ID
    public function getEmployeeName($employeeId)
    {
        $employee = \App\Models\Employee::find($employeeId);
        return $employee ? $employee->name : 'Unknown Employee';
    }

    // Approve Request: Change status to Completed
    public function approveRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Completed';
            $request->save();
            session()->flash('message', 'Request approved!');
        }
    }

    // Reject Request: Change status to Rejected
    public function rejectRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Rejected';
            $request->save();
            session()->flash('message', 'Request rejected!');
        }
    }

    // Confirm Request Deletion
    public function confirmDestroyRequest($requestId)
    {
        $this->confirmedId = $requestId;
    }

    // Delete Request
    public function destroyRequest()
    {
        $request = Request::find($this->confirmedId);
        if ($request) {
            $request->delete();
            session()->flash('message', 'Request deleted!');
        }
        $this->confirmedId = null; // Reset the confirmedId after deletion
    }

    // Render method to fetch requests with applied filters
    public function render()
    {
        $requests = Request::query();

        // Ensure only COE requests are fetched
        $requests->where('type', 'Certificate of Employment');  // Filter by request type

        // Search by employee name
        if (!empty($this->searchTerm)) {
            $requests->whereHas('employee', function($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $requests->where('request_date', $this->requestDate);
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $requests->where('status', $this->statusFilter);
        }

        $requests = $requests->paginate(10); // Paginate results

        return view('livewire.human-resource.coe', [
            'requests' => $requests,
        ]);
    }
}
