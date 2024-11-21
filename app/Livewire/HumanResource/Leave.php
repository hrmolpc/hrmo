<?php
namespace App\Livewire\HumanResource;

use Livewire\Component;
use App\Models\Request; // Import Request model
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log; 

class Leave extends Component
{
    use WithPagination;

    public $confirmedId = null; // To track which request is being deleted
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status
    public $searchTerm = ''; // For search by employee name

    public function mount()
    {
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

    // Approve Request: Change status to Completed
    public function approveRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Approved';
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

        $requests = Request::query()
        ->where('type', 'Leave'); // Filter by type

    

        $requests = $requests->paginate(10); // Paginate results

        return view('livewire.human-resource.leave', [
            'requests' => $requests,
        ]);
    }
}
