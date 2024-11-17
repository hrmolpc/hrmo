<?php
namespace App\Livewire\HumanResource;

use Livewire\Component;

class Leave extends Component
{
    public $leaveRecords = [];
    public $confirmedId = null;
    public $leaveRequest = [
        'id' => null,
        'employee_id' => null,
        'request_date' => '',
        'status' => '',
    ];
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
        // Find the leave request by ID
        $leave = collect($this->leaveRecords)->firstWhere('id', $leaveId);
        
        $this->leaveRequest = [
            'id' => $leave->id,
            'employee_id' => $leave->employee_id,
            'request_date' => $leave->request_date,
            'status' => $leave->status,
        ];
    }

    public function updateLeaveRequest()
    {
        // Update the leave request in the leaveRecords array
        $leaveIndex = array_search($this->leaveRequest['id'], array_column($this->leaveRecords, 'id'));
        
        if ($leaveIndex !== false) {
            $this->leaveRecords[$leaveIndex] = (object) $this->leaveRequest;
        }

        session()->flash('message', 'Leave request updated successfully!');
        $this->resetLeaveRequest();
    }

    public function confirmDestroyLeave($leaveId)
    {
        $this->confirmedId = $leaveId;
    }

    public function destroyLeave()
    {
        // Remove the confirmed leave request
        $this->leaveRecords = array_filter($this->leaveRecords, function($leave) {
            return $leave->id !== $this->confirmedId;
        });

        session()->flash('message', 'Leave request deleted successfully!');
        $this->confirmedId = null;
    }

    public function render()
    {
        // Eloquent pagination with filtering
        $leaveRecords = LeaveRequest::query()
            ->when($this->employeeInfo['firstName'], function($query) {
                $query->whereHas('employee', function($query) {
                    $query->where('first_name', 'like', '%' . $this->employeeInfo['firstName'] . '%');
                });
            })
            ->when($this->requestDate, function($query) {
                $query->where('request_date', $this->requestDate);
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->paginate(5);  // Paginate with 5 records per page
    
        return view('livewire.human-resource.leave', [
            'leaveRecords' => $leaveRecords,
        ]);
    }
    

    protected function filterLeaveRecords()
    {
        // Filter based on employee name
        $filteredRecords = $this->leaveRecords;
        if (!empty($this->employeeInfo['firstName'])) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                $employeeName = $this->getEmployeeName($leave->employee_id);
                return stripos($employeeName, $this->employeeInfo['firstName']) !== false;
            });
        }

        // Filter by request date
        if (!empty($this->requestDate)) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                return $leave->request_date === $this->requestDate;
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $filteredRecords = array_filter($filteredRecords, function($leave) {
                return $leave->status === $this->statusFilter;
            });
        }

        return $filteredRecords;
    }

    private function resetLeaveRequest()
    {
        $this->leaveRequest = [
            'id' => null,
            'employee_id' => null,
            'request_date' => '',
            'status' => '',
        ];
    }
}
