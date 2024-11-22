<?php
namespace App\Livewire\HumanResource;

use App\Models\Employee;
use App\Mail\RequestNotification; // Correct import
use App\Mail\RejectedRequestNotification; // Correct import
use Livewire\Component;
use App\Models\Request; // Import Request model
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class COE extends Component
{
    public function mount()
    {
    }
    public function getEmployeeName($id)
    {
        // Try using 'where' to match by the 'id' column
        $employee = \App\Models\Employee::where('employee_id', $id)->first();
    
        // Log the retrieval attempt
        Log::info('Employee retrieval attempt', ['employee_id' => $id, 'found' => $employee ? true : false]);
    
        // If the employee is found, return the full name or short name
        if ($employee) {
            $fullName = $employee->first_name . ' ' . $employee->last_name;
       
            return $fullName;
        }
    
        // If the employee is not found, return a default name
        return 'Unknown Employee';
    }
    
    

    public function approveRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Approved';
            $request->save();
            session()->flash('message', 'Request approved!');


            $employeeFullName = $this->getEmployeeName($request->employee_id);
            session()->flash('message', "Request approved for $employeeFullName!");

            Mail::to('dab.olarte@gmail.com')->send(new RequestNotification($request));
        }
    }

    public function rejectRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Rejected';
            $request->save();
            session()->flash('message', 'Request rejected!');

            $employeeFullName = $this->getEmployeeName($request->employee_id);
            session()->flash('message', "Request rejected for $employeeFullName!");

            Mail::to('dab.olarte@gmail.com')->send(new RejectedRequestNotification($request));
        }
    }


    public function confirmDestroyRequest($requestId)
    {
        $this->confirmedId = $requestId;
    }

    public function destroyRequest()
    {
        $request = Request::find($this->confirmedId);
        if ($request) {
            $request->delete();
            session()->flash('message', 'Request deleted!');
        }
        $this->confirmedId = null; // Reset the confirmedId after deletion
    }

    public function render()
    {
        $requests = Request::query()->where('type', 'Certificate of Employment')->paginate(10); // Filter by type

        return view('livewire.human-resource.coe', [
            'requests' => $requests,
        ]);
    }
}
