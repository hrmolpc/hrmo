<?php

namespace App\Livewire\HumanResource;

use Livewire\Component;

class COE extends Component
{
    public $coeRecords = [];
    public $confirmedId = null;
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status

    public function mount()
    {
        // Mock data for Certificate of Employment requests
        $this->coeRecords = [
            (object)[
                'id' => 1,
                'employee_id' => 101,
                'request_date' => '2024-10-20',
                'status' => 'Pending',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 102,
                'request_date' => '2024-10-25',
                'status' => 'Completed',
            ],
            (object)[
                'id' => 3,
                'employee_id' => 103,
                'request_date' => '2024-10-30',
                'status' => 'Pending',
            ],
            (object)[
                'id' => 4,
                'employee_id' => 104,
                'request_date' => '2024-11-05',
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

    public function showEditCOEModal($coeId)
    {
        // Logic to show edit modal (mock implementation)
    }

    public function confirmDestroyCOE($coeId)
    {
        // Logic to confirm deletion (mock implementation)
        $this->confirmedId = $coeId;
    }

    public function destroyCOE()
    {
        // Logic to delete a Certificate of Employment request (mock implementation)
        $this->coeRecords = array_filter($this->coeRecords, function($coe) {
            return $coe->id !== $this->confirmedId;
        });

        $this->confirmedId = null;
    }

    public function render()
    {
        $filteredRecords = $this->filterCOERecords();

        return view('livewire.human-resource.coe', [
            'coeRecords' => $filteredRecords,
        ]);
    }

    protected function filterCOERecords()
    {
        // Start with the base records
        $filteredRecords = $this->coeRecords;

        // Filter by employee name
        if (!empty($this->employeeInfo['firstName'])) {
            $filteredRecords = array_filter($filteredRecords, function($coe) {
                $employeeName = $this->getEmployeeName($coe->employee_id);
                return stripos($employeeName, $this->employeeInfo['firstName']) !== false;
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $filteredRecords = array_filter($filteredRecords, function($coe) {
                return $coe->request_date === $this->requestDate; // Match exact date
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $filteredRecords = array_filter($filteredRecords, function($coe) {
                return $coe->status === $this->statusFilter; // Match exact status
            });
        }

        return $filteredRecords;
    }
}
