<div>
@php
    $configData = Helper::appClasses();
    use App\Models\Employee;
    use Carbon\Carbon;
@endphp

@section('title', 'Dashboard')

@section('vendor-style')
@endsection

@section('page-style')
<style>
    .match-height > [class*='col'] {
        display: flex;
        flex-flow: column;
    }
    .match-height > [class*='col'] > .card {
        flex: 1 1 auto;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-tr {
        opacity: 0;
    }
    tr:hover .btn-tr {
        display: inline-block;
        opacity: 1;
    }
    tr:hover .td {
        color: #7367f0 !important;
    }
</style>
@endsection

@include('_partials/_alerts/alert-general')


<div>
    @if ($userRole == 'admin')


    @elseif ($userRole == 'employee')
       <div class="row">
        <div class="col-12 mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <h5 class="card-header d-flex justify-content-between align-items-center">
                            My Requests
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newRequestModal">
                                Add New Request
                            </button>
                        </h5>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-1">{{ __('ID') }}</th>
                                        <th>{{ __('Request Type') }}</th>
                                        <th style="text-align: center">{{ __('Request Date') }}</th>
                                        <th style="text-align: center">{{ __('Status') }}</th>
                                        <th style="text-align: center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($requests as $request)
                                        <tr>
                                            <td><strong>{{ $request->id }}</strong></td>
                                            <td class="td">{{ $request->type }}</td>
                                            <td style="text-align: center">{{ $request->created_at->format('m/d/Y') }}</td>
                                            <td style="text-align: center">{{ $request->status }}</td>
                                            <td style="text-align: center">
                                                <!-- Actions -->
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect" data-bs-toggle="modal" data-bs-target="#viewRequestModal{{ $request->id }}">
                                                    <span class="ti ti-eye"></span>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" wire:click="deleteRequest({{ $request->id }})">
                                                    <span class="ti ti-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No requests found.</td>
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

    {{-- New Request Modal --}}
    <div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="newRequestModalLabel" aria-hidden="true" wire:ignore>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 bg-light">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="modal-title mb-0 fw-semibold">Request Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-4">
                <form wire:submit.prevent="saveRequest">
                    <!-- Request Type Selection -->
                    <div class="mb-4">
                        <label for="requestType" class="form-label text-muted fw-medium">
                            <i class="bi bi-list-check me-2"></i>Request Type
                        </label>
                        <select class="form-select form-select-lg border-0 bg-light" wire:model="requestType" required>
                            <option value="">Select request type...</option>
                            <option value="Certificate of Employment">Certificate of Employment</option>
                            <option value="Payslip">Payslip</option>
                            <option value="Leave">Leave</option>
                            <option value="Service Records">Service Records</option>
                        </select>
                        @error('requestType') 
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Reason for Filing -->
                    <div class="mb-4">
                        <label for="requestDetails" class="form-label text-muted fw-medium">
                            <i class="bi bi-journal-text me-2"></i>Reason for Filing
                        </label>
                        <textarea 
                            class="form-control border-0 bg-light" 
                            wire:model="requestDetails" 
                            rows="3" 
                            placeholder="Enter your reason here..."
                            required></textarea>
                        @error('requestDetails') 
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Leave Dates (Conditional) -->
                    @if($requestType == 'Leave')
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="dateFrom" class="form-label text-muted fw-medium">
                                    <i class="bi bi-calendar3 me-2"></i>Date From
                                </label>
                                <input 
                                    type="date" 
                                    class="form-control border-0 bg-light" 
                                    wire:model="dateFrom" 
                                    required>
                                @error('dateFrom') 
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="dateTo" class="form-label text-muted fw-medium">
                                    <i class="bi bi-calendar3 me-2"></i>Date To
                                </label>
                                <input 
                                    type="date" 
                                    class="form-control border-0 bg-light" 
                                    wire:model="dateTo" 
                                    required>
                                @error('dateTo') 
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Attachment Input -->
                    <div class="mb-4">
                        <label for="attachment" class="form-label text-muted fw-medium">
                            <i class="bi bi-paperclip me-2"></i>Attachment
                        </label>
                        <input 
                            type="file" 
                            class="form-control border-0 bg-light" 
                            wire:model="attachment">
                        @error('attachment') 
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="pt-3 border-top">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-check-circle me-2"></i>Submit Request
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    {{-- View Request Modal --}}
    @foreach($requests as $request)
        <div class="modal fade" id="viewRequestModal{{ $request->id }}" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
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
                                        <span class="fw-bold text-muted me-2"><i class="bi bi-check-circle me-1"></i>Status:</span>
                                        <span class="badge 
                                            @switch($request->status)
                                                @case('Approved') bg-success @break
                                                @case('Pending') bg-warning @break
                                                @case('Rejected') bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{ $request->status }}
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

            {{-- Single Attachment (requestor_attachment column) --}}
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
                            <a href="{{ route('download.attachment', $request->id) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Download">
                                <i class="bi bi-download">Download</i>
                            </a>
                            <button 
                                onclick="window.open('{{ route('view.attachment', $request->id) }}', '_blank')" 
                                class="btn btn-sm btn-outline-secondary" 
                                title="View">
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

    @endforeach
    @else
        <p>Unknown role.</p>
    @endif
</div>
 


{{-- Modals --}}
@push('custom-scripts')
<script>
    function updateClock() {
        const now = new Date();
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

        const formattedDate = now.toLocaleDateString('en-US', dateOptions);
        const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

        document.getElementById('date').innerHTML = formattedDate;
        document.getElementById('time').innerHTML = formattedTime;
    }

    setInterval(updateClock, 1000); // Update every second
    updateClock(); // Initial call to display clock immediately
</script>
@endpush


<style>
.modal-content {
    border-radius: 12px;
}
.modal-header {
    background: linear-gradient(135deg, #3b8132 0%, #FFFFFF 100%);
}
</style>

</div>
