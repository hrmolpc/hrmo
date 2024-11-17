<?php
namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Request; 
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Employee extends Component
{
    use WithFileUploads;

    public $requestType;
    public $requestDetails;
    public $dateFrom;
    public $dateTo;
    public $attachment;
    public $requestToDelete; // Store the ID of the request to be deleted

    protected $rules = [
        'requestType' => 'required|string',
        'requestDetails' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,jpeg,png',
        'dateFrom' => 'nullable|date',
        'dateTo' => 'nullable|date|after_or_equal:dateFrom',
    ];

    // Save the request
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

        try {
            Request::create($data);
            session()->flash('message', 'Request submitted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'There was an error submitting your request.');
        }

        // Reset the form
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

    // Render the view without any filter applied
    public function render()
    {
        $requests = Request::all();

        return view('livewire.employee.employee', [
            'requests' => $requests
        ]);
    }
}
