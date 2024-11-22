<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use App\Mail\EmployeeRequestNotification; 
use App\Models\Employee;
use App\Models\Request;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
class Dashboard extends Component



{

    use WithFileUploads;
    public $requestTypeCounter = [];
    public $employmentStatusCount = [];
    public $inactiveEmploymentStatusCount = [];
    public $requests = [];
    public $myRequests = [];
    public $userRole;

    public $leaveCount = 0;
    public $coeCount = 0;
    public $serviceRecords = 0;
    public $payslipCount = 0;
    public $activeCount = 0;
    public $inActiveCount = 0;
    public $regularCount = 0;
    public $partTimeCount = 0;
    public $jobOrderCount = 0;
    public $casualCount = 0;
    public $consultantCount = 0;
    public $contractServicCount = 0;
    public $requestType;
    public $requestDetails;
    public $dateFrom;
    public $dateTo;
    public $attachment;

    public $confirmedId = null; // Store confirmed request ID for deletion


    protected $rules = [
        'requestType' => 'required|string',
        'requestDetails' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,jpeg,png',
        'dateFrom' => 'nullable|date',
        'dateTo' => 'nullable|date|after_or_equal:dateFrom',
    ];


    

    public function mount()
    
    {
        $user = Auth::user();
        $this->userRole = $user->account_type;
        $this->getAllRequests(); // Fetch a
        $this->getAllMyRequests(); // Fetch a
    }




public function getAllMyRequests()
{
    // Build the query and convert the result to an array
    $this->myRequests = collect(Request::query()
    ->where('employee_id', auth()->user()->employee_id)
    ->get()->toArray()); // Convert the collection to an array
}

    

    public function getAllRequests()
    {
        // Set the date to 2024-11-17 (testing with a past date)
        $dateToFilter = Carbon::createFromFormat('Y-m-d', '2024-11-17', 'Asia/Manila');
    
        // Get the start and end of that day in Asia/Manila timezone
        $startOfDay = $dateToFilter->startOfDay();
        $endOfDay = $dateToFilter->endOfDay();

        
        $this->myRequests = Request::query()
        ->where('employee_id', auth()->user()->employee_id);
    
        // Log the values to see what the start and end of the day look like
       // dd($startOfDay, $endOfDay);
    
        // Fetch requests created on 2024-11-17 using the range
        $this->requests = Request::query()
            //->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();

            $this->leaveCount = Request::query()
            ->where('type', 'Leave')
            ->count();

            $this->coeCount = Request::query()
            ->where('type', 'Certificate of Employment')
            ->count();

            $this->serviceRecords = Request::query()
            ->where('type', 'Service Records')
            ->count();

            $this->payslipCount = Request::query()
            ->where('type', 'Payslip')
            ->count();

                    // Count Employees by Employment Status (Active, Inactive, etc.)
        $this->activeCount = Employee::query()->where('is_active', '1')->count();
        $this->inActiveCount = Employee::query()->where('is_active', '0')->count();

        // Count Employees by Employment Type (Regular, Part-Time, Job Order, etc.)
        $this->regularCount = Employee::query()->where('employment_status', 'regular')->count();
        $this->partTimeCount = Employee::query()->where('employment_status', 'part_time')->count();
        $this->jobOrderCount = Employee::query()->where('employment_status', 'job_order')->count();
        $this->casualCount = Employee::query()->where('employment_status', 'casual')->count();
        $this->consultantCount = Employee::query()->where('employment_status', 'consultaant')->count();
        $this->contractServicCount = Employee::query()->where('employment_status', 'contact_of_service')->count();
  
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
    public function openRequestModal($requestId)
    {
        $this->emit('openModal', $requestId);
    }

    public function saveRequest()
    {
        try {
            $this->validate();
    
            $data = [
                'employee_id' => auth()->user()->employee_id,
                'type' => $this->requestType,
                'description' => $this->requestDetails,
                'status' => 'Pending',
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
                'is_active' => true,
                'deleted_by' => null,
                'requestor_attachment' => $this->uploadAttachment(),
                'approver_attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ];
    
            Request::create($data);
    
          //  Mail::to('hrmolaspinas@gmail.com')->send(new EmployeeRequestNotification($data));
    
            session()->flash('message', 'Request submitted successfully!');
            $this->reset();

            return redirect()->to(request()->header('Referer'));
        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting request. Please try again.');
            logger()->error('Request submission failed: ' . $e->getMessage());
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

            return redirect()->to(request()->header('Referer'));
            $this->confirmedId = null;
        } else {
            Log::error('Confirmed ID is null', ['confirmed_id' => $this->confirmedId]);
        }
    }
    

    public function uploadAttachment()
    {
        if ($this->attachment) {
            return $this->attachment->store('attachments', 'public');
        }
        return null;
    }

    public function downloadAttachment($requestId)
    {
        $request = Request::find($requestId);
        if (!$request || !$request->requestor_attachment) {
            abort(404, 'Attachment not found.');
        }

        $filePath = storage_path("app/public/{$request->requestor_attachment}");

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($filePath, basename($request->requestor_attachment));
    }

    public function viewAttachment($requestId)
    {
        $request = Request::find($requestId);
        if (!$request || !$request->requestor_attachment) {
            abort(404, 'Attachment not found.');
        }

        $filePath = storage_path("app/public/{$request->requestor_attachment}");

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->file($filePath);
    }
    

    public function render()
    {
        return view('livewire.dashboard', [
            'requests' => $this->requests,
            'myRequest' => $this->myRequests,
            'leaveCount' => $this->leaveCount,
            'coeCount' => $this->coeCount,
            'serviceRecords' => $this->serviceRecords,
            'payslipCount' => $this->payslipCount,
            'activeCount' => $this->activeCount,
            'inActiveCount' => $this->inActiveCount,
            'regularCount' => $this->regularCount,
            'partTimeCount' => $this->partTimeCount,
            'jobOrderCount' => $this->jobOrderCount,
            'casualCount' => $this->casualCount,
            'consultantCount' => $this->consultantCount,
            'contractServicCount' => $this->contractServicCount,
            'userRole' => $this->userRole,
        ]);
    }
}
