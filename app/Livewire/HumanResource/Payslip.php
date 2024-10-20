<?php

namespace App\Livewire\HumanResource;

use Livewire\Component;

class Payslip extends Component
{
    public $payslipRecords = [];
    public $confirmedId = null;
    public $employeeInfo = ['firstName' => ''];
    public $requestDate = ''; // For filtering by request date
    public $statusFilter = ''; // For filtering by status

    public function mount()
    {
        // Mock data for Payslip requests
        $this->payslipRecords = [
            (object)[
                'id' => 1,
                'employee_id' => 101,
                'request_date' => '2024-10-01',
                'status' => 'Processed',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 102,
                'request_date' => '2024-10-15',
                'status' => 'Pending',
            ],
            (object)[
                'id' => 3,
                'employee_id' => 103,
                'request_date' => '2024-10-20',
                'status' => 'Processed',
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

    public function showEditPayslipModal($payslipId)
    {
        // Logic to show edit modal (mock implementation)
    }

    public function confirmDestroyPayslip($payslipId)
    {
        // Logic to confirm deletion (mock implementation)
        $this->confirmedId = $payslipId;
    }

    public function destroyPayslip()
    {
        // Logic to delete a Payslip request (mock implementation)
        $this->payslipRecords = array_filter($this->payslipRecords, function($payslip) {
            return $payslip->id !== $this->confirmedId;
        });

        $this->confirmedId = null;
    }

    public function render()
    {
        $filteredRecords = $this->filterPayslipRecords();

        return view('livewire.human-resource.payslip', [
            'payslipRecords' => $filteredRecords,
        ]);
    }

    protected function filterPayslipRecords()
    {
        // Start with the base records
        $filteredRecords = $this->payslipRecords;

        // Filter by employee name
        if (!empty($this->employeeInfo['firstName'])) {
            $filteredRecords = array_filter($filteredRecords, function($payslip) {
                $employeeName = $this->getEmployeeName($payslip->employee_id);
                return stripos($employeeName, $this->employeeInfo['firstName']) !== false;
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $filteredRecords = array_filter($filteredRecords, function($payslip) {
                return $payslip->request_date === $this->requestDate; // Match exact date
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $filteredRecords = array_filter($filteredRecords, function($payslip) {
                return $payslip->status === $this->statusFilter; // Match exact status
            });
        }

        return $filteredRecords;
    }
}
