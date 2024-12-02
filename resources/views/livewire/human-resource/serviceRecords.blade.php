<div>
    @php
        $configData = Helper::appClasses();
    @endphp

    @section('title', 'Service Records')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Human Resource</li>
            <li class="breadcrumb-item active">Service Records</li>
        </ol>
    </nav>

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    <div class="row">
        <div class="col-13 mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <h5 class="card-header">{{ __('Service Records') }}</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-1">{{ __('ID') }}</th>
                                        <th>{{ __('Employee') }}</th>
                                        <th style="text-align: center">{{ __('Request Date') }}</th>
                                        <th style="text-align: center">{{ __('Status') }}</th>
                                        <th style="text-align: center"> Remarks </th>
                                        <th style="text-align: center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($requests as $request)
                                        <tr>
                                            <td><strong>{{ $request->id }}</strong></td>
                                            <td class="td">{{ $this->getEmployeeName($request->employee_id) }}</td>
                                            <td style="text-align: center">{{ \Carbon\Carbon::parse($request->created_at)->format('F j, Y') }}</td>
                                            <td style="text-align: center">{{ $request->status == 'Pending' ? 'On-Process' : $request->status }}
                                            </td>
                                            <td style="text-align: center">
    @if ($request['is_active'] == 0)
    This hasn't been viewed yet.
    @elseif ($request['is_active'] == 1)
        Request has been viewed and is currently being processed (2-3 days).
    @else
        Request status is currently unavailable.
    @endif
</td>
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
                                                @endif

                                                <!-- Delete Button -->
                                                <button type="button" wire:click="confirmDestroyRequest({{ $request->id }})" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $request->id }}">
                                                    <span class="ti ti-trash"></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal-{{ $request->id }}" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header bg-primary text-white py-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-light text-primary me-3">{{ $request->type }}</span>
                                                            <h5 class="modal-title">Request Details</h5>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-4">
                                                            <div class="col-md-6">
                                                                <div class="card border-0 h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-muted mb-3">
                                                                            Request Information
                                                                        </h6>
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-calendar-check me-1"></i>Request Date:</span>
                                                                                {{ \Carbon\Carbon::parse($request->created_at)->format('F j, Y') }}
                                                                            </li>
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-person me-1"></i>Employee Name:</span>
                                                                                {{ $this->getEmployeeName($request->employee_id) }}
                                                                            </li>
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-check-circle me-1"></i>Status:</span>
                                                                                <span class="badge 
                                                                                    @switch($request->status)
                                                                                        @case('Approved') bg-success @break
                                                                                        @case('Pending') bg-warning @break
                                                                                        @case('Rejected') bg-danger @break
                                                                                        @default bg-secondary
                                                                                    @endswitch
                                                                                ">
                                                                                {{ $request->status == 'Pending' ? 'On-Process' : $request->status }}
                                                                                </span>
                                                                            </li>
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-tag me-1"></i>Type:</span>
                                                                                {{ $request->type }}
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card border-0 h-100">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-muted mb-3">
                                                                            <i class="bi bi-journal-text me-2"></i>Description
                                                                        </h6>
                                                                        <div class="alert alert-light border-0" role="alert">
                                                                            <p class="mb-0">{{ $request->description }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Attachments Section -->
                                                            <div class="col-12">
                                                                <div class="card border-0">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title text-muted mb-3">
                                                                            <i class="bi bi-paperclip me-2"></i>Attachments
                                                                        </h6>
                                                                        @if ($request->requestor_attachment)
                                                                            <div class="list-group mb-3">
                                                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="bi 
                                                                                            @switch(pathinfo($request->requestor_attachment, PATHINFO_EXTENSION))
                                                                                                @case('pdf') bi-file-pdf text-danger @break
                                                                                                @case('doc') 
                                                                                                @case('docx') bi-file-word text-primary @break
                                                                                                @case('xls') 
                                                                                                @case('xlsx') bi-file-excel text-success @break
                                                                                                @case('jpg') 
                                                                                                @case('jpeg') 
                                                                                                @case('png') 
                                                                                                @case('gif') bi-file-image text-info @break
                                                                                                @default bi-file text-secondary
                                                                                            @endswitch
                                                                                        me-3 fs-4"></i>
                                                                                        <span>{{ basename($request->requestor_attachment) }}</span>
                                                                                    </div>
                                                                                    <div class="btn-group" role="group">
                                                                                        <a href="{{ route('download.attachment', $request->id) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                                                                            <i class="bi bi-download">Download</i>
                                                                                        </a>
                                                                                        <button onclick="window.open('{{ route('view.attachment', $request->id) }}', '_blank')" class="btn btn-sm btn-outline-secondary" title="View">
                                                                                            <i class="bi bi-eye">View</i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        {{-- No Attachments --}}
                                                                        @if (!$request->requestor_attachment && (!$request->attachments || $request->attachments->count() === 0))
                                                                            <div class="alert alert-light text-muted text-center" role="alert">
                                                                                <i class="bi bi-exclamation-circle me-2"></i>No attachments found.
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click.prevent="viewRequest({{ $request->id }})">
                                                            <i class="bi bi-x-circle me-2"></i>Mark as Viewed
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-2"></i>Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                <!-- Approve/Reject Modal -->
<div class="modal fade" id="approveRejectModal-{{ $request->id }}" wire:ignore tabindex="-1" aria-labelledby="approveRejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-light text-danger me-3">Confirm Action</span>
                    <h5 class="modal-title" id="deleteModalLabel">Approve / Reject Request</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="approveRequest({{ $request->id }})" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body">
                                    <div class="alert alert-warning border-0" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-exclamation-triangle me-3 fs-4"></i>
                                            <p class="mb-0">
                                                Are you sure you want to 
                                                @if($request->status == 'Pending')
                                                    approve
                                                @else
                                                    reject
                                                @endif 
                                                this request?
                                            </p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled mt-3">
                                        <li class="mb-2">
                                            <span class="fw-bold text-muted me-2"><i class="bi bi-person me-1"></i>Employee Name:</span>
                                            {{ $this->getEmployeeName($request->employee_id) }}
                                        </li>
                                        <li class="mb-2">
                                            <span class="fw-bold text-muted me-2"><i class="bi bi-calendar-check me-1"></i>Request Date:</span>
                                            {{ \Carbon\Carbon::parse($request->created_at)->format('F j, Y') }}
                                        </li>
                                    </ul>

                                    <!-- Comment Section -->
                                    <div class="mb-3">
                                        <label for="approverComment" class="form-label"><i class="bi bi-pencil me-1"></i>Notes:</label>
                                        <textarea class="form-control" id="approverComment" wire:model="notes" rows="3" placeholder="Enter your notes here..."></textarea>
                                    </div>

                                    <!-- Attachment Section -->
                                    <div class="mb-4">
                                        <label for="approverAttachment" class="form-label text-muted fw-medium">
                                            <i class="bi bi-paperclip me-2"></i>Attachment
                                        </label>
                                        <input 
                                            id="approverAttachment"
                                            type="file" 
                                            class="form-control border-0 bg-light" 
                                            wire:model="attachment"
                                  
                                        >
                                        @error('attachment') 
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Approve
                        </button>
                        <button type="button" class="btn btn-danger" wire:click.prevent="rejectRequest({{ $request->id }})">
                            <i class="bi bi-x-circle me-2"></i>Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal-{{ $request->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header bg-danger text-white py-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-light text-danger me-3">Confirm Deletion</span>
                                                            <h5 class="modal-title" id="deleteModalLabel">Delete Request</h5>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-4">
                                                            <div class="col-12">
                                                                <div class="card border-0">
                                                                    <div class="card-body">
                                                                        <div class="alert alert-danger border-0" role="alert">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="bi bi-trash me-3 fs-4"></i>
                                                                                <p class="mb-0">Are you sure you want to permanently delete this request?</p>
                                                                            </div>
                                                                        </div>
                                                                        <ul class="list-unstyled mt-3">
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-person me-1"></i>Employee Name:</span>
                                                                                {{ $this->getEmployeeName($request->employee_id) }}
                                                                            </li>
                                                                            <li class="mb-2">
                                                                                <span class="fw-bold text-muted me-2"><i class="bi bi-calendar-check me-1"></i>Request Date:</span>
                                                                                {{ \Carbon\Carbon::parse($request->created_at)->format('F j, Y') }}
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-2"></i>Cancel
                                                        </button>
                                                        <!-- This button calls the method to confirm deletion -->
                                                        <button type="button" class="btn btn-danger" wire:click.prevent="destroyRequest" data-bs-dismiss="modal">
                                                            <i class="bi bi-trash me-2"></i>Delete
                                                        </button>
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

    <style>
        .modal-content {
            border-radius: 12px;
        }
        .modal-header {
            background: linear-gradient(135deg, #3b8132 0%, #FFFFFF 100%);
        }
    </style>

    <script>
        window.addEventListener('close-modal', event => {
            var modalId = event.detail.modalId;
            var modalElement = document.getElementById(modalId);
            var modal = new bootstrap.Modal(modalElement);
            modal.hide();
        });
    </script>

<script>
    // This function will open the modal if it's not already open
    function openModal(modalId) {
        var modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    document.addEventListener('livewire:load', function () {
    Livewire.on('closeModal', (requestId) => {
        // Use Bootstrap's modal method to hide the modal
        var modal = new bootstrap.Modal(document.getElementById('approveRejectModal-' + requestId));
        modal.hide();
    });
});

</script>


</div>
