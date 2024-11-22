<?php

namespace App\Livewire;

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
    public $requestToDelete;

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
        $this->countEmploymentStatus();
        $this->requestTypeCount();
        $this->countInactiveEmploymentStatus();
        $this->getAllRequests();
    }

    public function countEmploymentStatus()
    {
        $this->employmentStatusCount = Employee::query()
            ->where('is_active', 1)
            ->select('employment_status', \DB::raw('COUNT(*) as count'))
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status')
            ->toArray();
    }

    public function countInactiveEmploymentStatus()
    {
        $this->inactiveEmploymentStatusCount = Employee::query()
            ->where('is_active', 0)
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
        $dateToFilter = Carbon::createFromFormat('Y-m-d', '2024-11-17', 'Asia/Manila');
        $startOfDay = $dateToFilter->startOfDay();
        $endOfDay = $dateToFilter->endOfDay();

        $this->requests = Request::query()
            //->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();
    }


    public function getEmployeeName($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->whereNull('deleted_at')->first();

        return optional($employee)->first_name . ' ' . optional($employee)->last_name ?? 'Unknown Employee';
    }

    public function saveRequest()
    {
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


        Mail::to('hrmolaspinas@gmail.com')->send(new EmployeeRequestNotification($data));

        session()->flash('message', 'Request submitted successfully!');
        $this->reset();
    }

    public function setRequestToDelete($requestId)
    {
        $this->requestToDelete = $requestId;
    }

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
            'employmentStatusCount' => $this->employmentStatusCount,
            'userRole' => $this->userRole,
            'requestTypeCounter' => $this->requestTypeCounter,
            'inactiveEmploymentStatusCount' => $this->inactiveEmploymentStatusCount,
            'requests' => $this->requests,
        ]);
    }
}
