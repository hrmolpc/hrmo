<?php

namespace App\Livewire\HumanResource;

use Livewire\Component;

class ServiceRecords extends Component
{
    public $serviceRecords = [];
    public $confirmedId = null;
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status

    // For modals
    public $viewRecordId = null;
    public $approveRecordId = null;
    public $rejectRecordId = null;
    public $reasonForRequest = '';
    public $attachment = '';
    public $approvalDocument = '';

    public function mount()
    {
        // Mock data for Service Records
        $this->serviceRecords = [
            (object)[
                'id' => 1,
                'employee_id' => 101,
                'request_date' => '2024-10-01',
                'status' => 'Active',
                'reason_for_request' => 'Need a day off for personal reasons.',
                'attachments' => 'path/to/attachment1.pdf',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 102,
                'request_date' => '2024-10-15',
                'status' => 'Inactive',
                'reason_for_request' => 'Medical leave.',
                'attachments' => 'path/to/attachment2.pdf',
            ],
            (object)[
                'id' => 3,
                'employee_id' => 103,
                'request_date' => '2024-10-20',
                'status' => 'Active',
                'reason_for_request' => 'Family commitment.',
                'attachments' => 'path/to/attachment3.pdf',
            ],
            (object)[
                'id' => 4,
                'employee_id' => 104,
                'request_date' => '2024-10-30',
                'status' => 'Inactive',
                'reason_for_request' => 'Vacation.',
                'attachments' => 'path/to/attachment4.pdf',
            ],
        ];
    }

    public function getEmployeeName($id)
    {
        // Mock employee names
        $employees = [
            101 => 'Alice Smith',
            102 => 'Bob Johnson',
            103 => 'Charlie Brown',
            104 => 'Daisy Williams',
        ];

        return $employees[$id] ?? 'Unknown Employee';
    }

    public function showViewModal($recordId)
    {
        $this->viewRecordId = $recordId;
    }

    public function showApproveModal($recordId)
    {
        $this->approveRecordId = $recordId;
    }

    public function showRejectModal($recordId)
    {
        $this->rejectRecordId = $recordId;
    }

    public function confirmDestroyServiceRecord($recordId)
    {
        // Logic to confirm deletion (mock implementation)
        $this->confirmedId = $recordId;
    }

    public function destroyServiceRecord()
    {
        // Logic to delete a Service Record (mock implementation)
        $this->serviceRecords = array_filter($this->serviceRecords, function($record) {
            return $record->id !== $this->confirmedId;
        });

        $this->confirmedId = null;
    }

    public function approveServiceRecord()
    {
        // Logic to approve the service record (mock implementation)
        // Implement the logic to handle the approval
        $this->serviceRecords = array_map(function ($record) {
            if ($record->id === $this->approveRecordId) {
                $record->status = 'Approved';
                // Save the approval document here if required
            }
            return $record;
        }, $this->serviceRecords);
        
        $this->approveRecordId = null;
    }

    public function rejectServiceRecord()
    {
        // Logic to reject the service record (mock implementation)
        // Implement the logic to handle the rejection
        $this->serviceRecords = array_map(function ($record) {
            if ($record->id === $this->rejectRecordId) {
                $record->status = 'Rejected';
            }
            return $record;
        }, $this->serviceRecords);

        $this->rejectRecordId = null;
    }

    public function render()
    {
        $filteredRecords = $this->filterServiceRecords();

        return view('livewire.human-resource.serviceRecords', [
            'serviceRecords' => $filteredRecords,
        ]);
    }

    protected function filterServiceRecords()
    {
        // Start with the base records
        $filteredRecords = $this->serviceRecords;

        // Filter by employee name
        if (!empty($this->employeeInfo['firstName'])) {
            $filteredRecords = array_filter($filteredRecords, function($record) {
                $employeeName = $this->getEmployeeName($record->employee_id);
                return stripos($employeeName, $this->employeeInfo['firstName']) !== false;
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $filteredRecords = array_filter($filteredRecords, function($record) {
                return $record->request_date === $this->requestDate; // Match exact date
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $filteredRecords = array_filter($filteredRecords, function($record) {
                return $record->status === $this->statusFilter; // Match exact status
            });
        }

        return $filteredRecords;
    }
}
