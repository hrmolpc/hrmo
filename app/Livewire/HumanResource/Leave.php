<?php

namespace App\Livewire\HumanResource;

use Livewire\Component;

class Leave extends Component
{
    public $leaveRecords = [];
    public $confirmedId = null;
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status

    public function mount()
    {
        // Mock data for Leave requests
        $this->leaveRecords = [
            (object)[
                'id' => 1,
                'employee_id' => 101,
                'request_date' => '2024-10-15',
                'status' => 'Pending',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 102,
                'request_date' => '2024-10-20',
                'status' => 'Approved',
            ],
            (object)[
                'id' => 3,
                'employee_id' => 103,
                'request_date' => '2024-10-25',
                'status' => 'Pending',
            ],
            (object)[
                'id' => 4,
                'employee_id' => 104,
                'request_date' => '2024-10-30',
                'status' => 'Rejected',
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

    public function showEditLeaveModal($leaveId)
    {
        // Logic to show edit modal (mock implementation)
    }

    public function confirmDestroyLeave($leaveId)
    {
        // Logic to confirm deletion (mock implementation)
        $this->confirmedId = $leaveId;
    }

    public function destroyLeave()
    {
        // Logic to delete a Leave request (mock implementation)
        $this->leaveRecords = array_filter($this->leaveRecords, function($leave) {
            return $leave->id !== $this->confirmedId;
        });

        $this->confirmedId = null;
    }

    public function render()
    {
        $filteredRecords = $this->filterLeaveRecords();

        return view('livewire.human-resource.leave', [
            'leaveRecords' => $filteredRecords,
        ]);
    }

    protected function filterLeaveRecords()
    {
        // Start with the base records
        $filteredRecords = $this->leaveRecords;

        // Filter by employee name
        if (!empty($this->employeeInfo['firstName'])) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                $employeeName = $this->getEmployeeName($leave->employee_id);
                return stripos($employeeName, $this->employeeInfo['firstName']) !== false;
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                return $leave->request_date === $this->requestDate; // Match exact date
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                return $leave->status === $this->statusFilter; // Match exact status
            });
        }

        return $filteredRecords;
    }
}
