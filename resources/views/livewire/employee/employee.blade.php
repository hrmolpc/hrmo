<div>
    @php
        $configData = Helper::appClasses();
    @endphp

    @section('title', 'Employee')

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

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
    <div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="newRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRequestModalLabel">New Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveRequest">
                        <div class="mb-3">
                            <label for="requestType" class="form-label">Request Type</label>
                            <select class="form-select" wire:model="requestType" required>
                                <option value="">Choose...</option>
                                <option value="Certificate of Employment">Certificate of Employment</option>
                                <option value="Payslip">Payslip</option>
                                <option value="Leave">Leave</option>
                                <option value="Service Records">Service Records</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestDetails" class="form-label">Reason for filing</label>
                            <textarea class="form-control" wire:model="requestDetails" rows="3" required></textarea>
                        </div>
                        <div class="mb-3" id="leaveDates" style="display: none;">
                            <label for="dateFrom" class="form-label">Date From</label>
                            <input type="date" class="form-control" wire:model="dateFrom">
                        </div>
                        <div class="mb-3" id="leaveDatesTo" style="display: none;">
                            <label for="dateTo" class="form-label">Date To</label>
                            <input type="date" class="form-control" wire:model="dateTo">
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment</label>
                            <input type="file" class="form-control" wire:model="attachment">
                        </div>

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            Submit Request
                        </button>
                    </form>

                    <!-- Loading Spinner -->
                    <div wire:loading>
                        <span>Loading...</span> <!-- You can replace this with a spinner -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- View Request Modal --}}
    @foreach($requests as $request)
        <div class="modal fade" id="viewRequestModal{{ $request->id }}" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewRequestModalLabel">Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Request Type:</strong> {{ $request->type }}</p>
                        <p><strong>Description:</strong> {{ $request->description }}</p>
                        <p><strong>Status:</strong> {{ $request->status }}</p>
                        <p><strong>Request Date:</strong> {{ $request->created_at->format('m/d/Y') }}</p>
                        @if($request->requestor_attachment)
                            <p><strong>Attachment:</strong> <a href="{{ Storage::url($request->requestor_attachment) }}" target="_blank">View Attachment</a></p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
