<div>
    @php
        $configData = Helper::appClasses();
    @endphp

    @section('title', 'COE')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Human Resource</li>
            <li class="breadcrumb-item active">Certificate of Employment Requests</li>
        </ol>
    </nav>

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    <div class="row">
        <div class="col-13 mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <h5 class="card-header">{{ __('Certificate of Employment Requests')}}</h5>
                        <div class="table-responsive text-nowrap">
                            <div class="row mx-4 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label w-100">Search by Employee Name</label>
                                    <input wire:model='searchTerm' class="form-control @error('searchTerm') is-invalid @enderror" type="text" />
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
                                        <option value="Completed">Completed</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-1">{{ __('ID') }}</th>
                                        <th>{{ __('Employee') }}</th>
                                        <th style="text-align: center">{{ __('Request Date') }}</th>
                                        <th style="text-align: center">{{ __('Status') }}</th>
                                        <th style="text-align: center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($requests as $request)
                                        <tr>
                                            <td><strong>{{ $request->id }}</strong></td>
                                            <td class="td">{{ $this->getEmployeeName($request->employee_id) }}</td>
                                            <td style="text-align: center">{{ $request->request_date }}</td>
                                            <td style="text-align: center">{{ $request->status }}</td>
                                            <td style="text-align: center">
                                                <!-- View Button -->
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect" data-bs-toggle="modal" data-bs-target="#viewModal-{{ $request->id }}">
                                                    <span class="ti ti-eye"></span>
                                                </button>

                                                <!-- Approve/Reject Buttons -->
                                                @if ($request->status == 'Pending')
                                                    <button type="button" class="btn btn-sm btn-success rounded-pill btn-icon waves-effect" data-bs-toggle="modal" data-bs-target="#approveRejectModal-{{ $request->id }}">
                                                        <span class="ti ti-check"></span>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger rounded-pill btn-icon waves-effect" data-bs-toggle="modal" data-bs-target="#approveRejectModal-{{ $request->id }}">
                                                        <span class="ti ti-x"></span>
                                                    </button>
                                                @endif

                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $request->id }}">
                                                    <span class="ti ti-trash"></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal-{{ $request->id }}" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel">Request Details - {{ $request->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Employee Name:</strong> {{ $this->getEmployeeName($request->employee_id) }}</p>
                                                        <p><strong>Request Date:</strong> {{ $request->request_date }}</p>
                                                        <p><strong>Status:</strong> {{ $request->status }}</p>
                                                        <p><strong>Type:</strong> {{ $request->type }}</p>
                                                        <p><strong>Description:</strong> {{ $request->description }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Approve/Reject Modal -->
                                        <div class="modal fade" id="approveRejectModal-{{ $request->id }}" tabindex="-1" aria-labelledby="approveRejectModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="approveRejectModalLabel">Confirm Action</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to {{ $request->status == 'Pending' ? 'approve' : 'reject' }} this request?</p>
                                                        <p><strong>Employee Name:</strong> {{ $this->getEmployeeName($request->employee_id) }}</p>
                                                        <p><strong>Request Date:</strong> {{ $request->request_date }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary" wire:click.prevent="approveRequest({{ $request->id }})" data-bs-dismiss="modal">Approve</button>
                                                        <button type="button" class="btn btn-danger" wire:click.prevent="rejectRequest({{ $request->id }})" data-bs-dismiss="modal">Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal-{{ $request->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this request?</p>
                                                        <p><strong>Employee Name:</strong> {{ $this->getEmployeeName($request->employee_id) }}</p>
                                                        <p><strong>Request Date:</strong> {{ $request->request_date }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" wire:click.prevent="destroyRequest" data-bs-dismiss="modal">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                            {{ $requests->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
