<div>
    @section('title', 'Service Records')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Human Resource') }}</li>
            <li class="breadcrumb-item active">{{ __('Service Records') }}</li>
        </ol>
    </nav>

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    <div class="row">
        <div class="col-12 mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <h5 class="card-header">{{ __('Service Records')}}</h5>
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
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
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
                                    @forelse($serviceRecords as $record)
                                        <tr>
                                            <td><strong>{{ $record->id }}</strong></td>
                                            <td class="td">{{ $this->getEmployeeName($record->employee_id) }}</td>
                                            <td style="text-align: center">{{ $record->request_date }}</td>
                                            <td style="text-align: center">{{ $record->status }}</td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect" wire:click.prevent="showViewModal({{ $record->id }})" data-bs-toggle="modal" data-bs-target="#viewModal">
                                                    <span class="ti ti-eye"></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success rounded-pill btn-icon waves-effect" wire:click.prevent="showApproveModal({{ $record->id }})" data-bs-toggle="modal" data-bs-target="#approveModal">
                                                    <span class="ti ti-check"></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" wire:click.prevent="showRejectModal({{ $record->id }})" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                    <span class="ti ti-trash"></span>
                                                </button>
                                                @if ($confirmedId === $record->id)
                                                    <button wire:click.prevent="destroyServiceRecord" type="button" class="btn btn-xs btn-danger waves-effect waves-light">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">{{ __('Service Record Details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($viewRecordId)
                        @php
                            $record = collect($serviceRecords)->firstWhere('id', $viewRecordId);
                        @endphp
                        <h5>{{ __('Employee: ') . $this->getEmployeeName($record->employee_id) }}</h5>
                        <p><strong>{{ __('Request Date: ') }}</strong>{{ $record->request_date }}</p>
                        <p><strong>{{ __('Reason for Request: ') }}</strong>{{ $record->reason_for_request }}</p>
                        <p><strong>{{ __('Attachments: ') }}</strong><a href="{{ asset($record->attachments) }}" target="_blank">View Document</a></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">{{ __('Approve Service Record') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($approveRecordId)
                        @php
                            $record = collect($serviceRecords)->firstWhere('id', $approveRecordId);
                        @endphp
                        <h5>{{ __('Are you sure you want to approve this request?') }}</h5>
                        <p><strong>{{ __('Employee: ') . $this->getEmployeeName($record->employee_id) }}</strong></p>
                        <p><strong>{{ __('Request Date: ') }}</strong>{{ $record->request_date }}</p>
                        <p><strong>{{ __('Attachments: ') }}</strong><a href="{{ asset($record->attachments) }}" target="_blank">View Document</a></p>
                        <label for="approvalDocument" class="form-label">{{ __('Attach approval document') }}</label>
                        <input type="file" wire:model="approvalDocument" class="form-control">
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="approveServiceRecord" data-bs-dismiss="modal">{{ __('Approve') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">{{ __('Reject Service Record') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($rejectRecordId)
                        @php
                            $record = collect($serviceRecords)->firstWhere('id', $rejectRecordId);
                        @endphp
                        <h5>{{ __('Are you sure you want to reject this request?') }}</h5>
                        <p><strong>{{ __('Employee: ') . $this->getEmployeeName($record->employee_id) }}</strong></p>
                        <p><strong>{{ __('Request Date: ') }}</strong>{{ $record->request_date }}</p>
                        <p><strong>{{ __('Attachments: ') }}</strong><a href="{{ asset($record->attachments) }}" target="_blank">View Document</a></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-danger" wire:click.prevent="rejectServiceRecord" data-bs-dismiss="modal">{{ __('Reject') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
