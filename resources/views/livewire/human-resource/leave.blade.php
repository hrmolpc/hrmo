<div>
    @section('title', 'Leave Requests')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Human Resource') }}</li>
            <li class="breadcrumb-item active">{{ __('Leave Requests') }}</li>
        </ol>
    </nav>

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    <div class="row">
        <div class="col-12 mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <h5 class="card-header">{{ __('Leave Requests') }}</h5>
                        <div class="table-responsive text-nowrap">
                            <div class="row mx-4 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label w-100">Search by Employee Name</label>
                                    <input wire:model='employeeInfo.firstName' class="form-control @error('employeeInfo.firstName') is-invalid @enderror" type="text" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label w-100">Filter by Request Date</label>
                                    <input wire:model='requestDate' class="form-control @error('requestDate') is-invalid @enderror" type="date" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label w-100">Filter by Status</label>
                                    <select wire:model='statusFilter' class="form-select @error('statusFilter') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-1">{{ __('ID') }}</th>
                                        <th>{{ __('Employee') }}</th>
                                        <th class="col-1">{{ __('Request Date') }}</th>
                                        <th style="text-align: center">{{ __('Status') }}</th>
                                        <th style="text-align: center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($leaveRecords as $leave)
                                        <tr>
                                            <td><strong>{{ $leave->id }}</strong></td>
                                            <td class="td">{{ $this->getEmployeeName($leave->employee_id) }}</td>
                                            <td style="text-align: center">{{ $leave->request_date }}</td>
                                            <td style="text-align: center">{{ $leave->status }}</td>
                                            <td style="text-align: center">
                                                <!-- View Button -->
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect" wire:click.prevent="showEditLeaveModal({{ $leave->id }})" data-bs-toggle="modal" data-bs-target="#leaveModal">
                                                    <span class="ti ti-eye"></span>
                                                </button>
                                                
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-sm btn-success rounded-pill btn-icon waves-effect" wire:click.prevent="showEditLeaveModal({{ $leave->id }})" data-bs-toggle="modal" data-bs-target="#leaveModal">
                                                    <span class="ti ti-check"></span>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" wire:click.prevent="confirmDestroyLeave({{ $leave->id }})">
                                                    <span class="ti ti-trash"></span>
                                                </button>

                                                <!-- Deletion Confirmation -->
                                                @if ($confirmedId === $leave->id)
                                                    <button wire:click.prevent="destroyLeave" type="button" class="btn btn-xs btn-danger waves-effect waves-light">
                                                        {{ __('Sure?') }}
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="mt-2 mb-2" style="text-align: center">
                                                    <p class="mb-4 mx-2">
                                                        {{ __('No data found!') }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $leaveRecords->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing and Editing Leave Request -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModalLabel">{{ __('Leave Request Details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateLeaveRequest">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Employee Name') }}</label>
                            <input type="text" class="form-control" value="{{ $this->getEmployeeName($leaveRequest['employee_id'] ?? '') }}" readonly />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Request Date') }}</label>
                            <input type="date" class="form-control" wire:model="leaveRequest.request_date" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" wire:model="leaveRequest.status">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
