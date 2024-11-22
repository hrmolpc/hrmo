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

class Leave extends Component
{
    public $confirmedId = null; // Store confirmed request ID for deletion
    public $notes = '';

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


    public function getEmail($id)
    {
        // Try using 'where' to match by the 'id' column
        $employee = \App\Models\Employee::where('employee_id', $id)->first();
    
        // Log the retrieval attempt
        Log::info('Employee retrieval attempt', ['employee_id' => $id, 'found' => $employee ? true : false]);
    
        // If the employee is found, return the full name or short name
        if ($employee) {
            $fullName = $employee->email;
       
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
            $request->notes = $this->notes;
          //  $request->approver_attachment = $this->uploadAttachment();
            $request->save();
            session()->flash('message', 'Request approved!');

            $getEmpEmail = $this->getEmail($request->employee_id);
            $employeeFullName = $this->getEmployeeName($request->employee_id);
            session()->flash('message', "Request approved for $employeeFullName!");

            Mail::to($getEmpEmail)->send(new RequestNotification($request));
            return redirect()->to(request()->header('Referer'));
        }
    }

    public function rejectRequest($requestId)
    {
        $request = Request::find($requestId);
        if ($request) {
            $request->status = 'Rejected';
            $request->notes = $this->notes;
            //$request->approver_attachment = $this->uploadAttachment();
            $request->save();
            session()->flash('message', 'Request rejected!');

            $getEmpEmail = $this->getEmail($request->employee_id);
            $employeeFullName = $this->getEmployeeName($request->employee_id);
            session()->flash('message', "Request rejected for $employeeFullName!");

            Mail::to($getEmpEmail)->send(new RequestNotification($request));
            return redirect()->to(request()->header('Referer'));
        }
    }


    public function confirmDestroyRequest($requestId)
    {
        $this->confirmedId = $requestId;
        Log::info('Confirmed request ID for deletion', ['request_id' => $this->confirmedId]);  // Log to verify
    
    }

    public function destroyRequest()
    {
        // Log the method entry
        Log::info('Entered destroyRequest method', ['confirmed_id' => $this->confirmedId]);
    
        // Perform the deletion only if confirmedId is not null
        if ($this->confirmedId) {
            $request = Request::find($this->confirmedId);
            
            // If the request is found, delete it
            if ($request) {
                Log::info('Deleting request', ['request_id' => $request->id]);
                $request->delete();
                session()->flash('message', 'Request deleted!');
            } else {
                // Log if the request is not found
                Log::error('Request not found for deletion', ['request_id' => $this->confirmedId]);
                session()->flash('error', 'Request not found.');
            }
    
            // Reset the confirmedId after deletion
            $this->confirmedId = null;
        } else {
            Log::error('Confirmed ID is null', ['confirmed_id' => $this->confirmedId]);
        }
    }
    

    public function render()
    {
        $requests = Request::query()->where('type', 'Leave')->paginate(10); // Filter by type

        return view('livewire.human-resource.leave', [
            'requests' => $requests,
        ]);
    }
}
