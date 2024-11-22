<div>

  @php
  $configData = Helper::appClasses();
  use App\Models\Employee;
  use Carbon\Carbon;
  @endphp

  @section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection

  @section('title', 'Dashboard')

  @section('vendor-style')

  @endsection

  @section('page-style')
  <style>
    .match-height>[class*='col'] {
      display: flex;
      flex-flow: column;
    }

    .match-height>[class*='col']>.card {
      flex: 1 1 auto;
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

  {{-- Alerts --}}
  @include('_partials/_alerts/alert-general')

  {{-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
      </li>
    </ol>
  </nav> --}}

  <div>
    @if ($userRole == 'admin')

    <div class="row match-height">
  <div class="col-xl-4 mb-4 col-lg-5 col-12">
  <div class="card h-50">
    <div class="card-header pb-0">
    <div class="text-center mt-3 mb-5">
        <img src="{{ asset('assets/img/logo/lpclogo.png') }}" class="img-fluid" 
             style="object-fit: contain; max-width: 60%; max-height: 100%;">
      </div>
  
    </div>
    
    
    <div class="card-body h-50 d-flex flex-column">
      <div class="calendar  ">
        <div class="d-flex justify-content-between mb-3">
          <button id="prev" class="btn btn-outline-secondary"><i class="ti ti-arrow-left"></i></button>
          <h5 id="monthYear" class="mb-0"></h5>
          <button id="next" class="btn btn-outline-secondary"><i class="ti ti-arrow-right"></i></button>
        </div>
        <div class="row text-center font-weight-bold">
          <div class="col text-primary">Sun</div>
          <div class="col text-primary">Mon</div>
          <div class="col text-primary">Tue</div>
          <div class="col text-primary">Wed</div>
          <div class="col text-primary">Thu</div>
          <div class="col text-primary">Fri</div>
          <div class="col text-primary">Sat</div>
        </div>
        <div id="days" class="row"></div>
      </div>
    
       <div class="announcement-card card mb-4">
  <div class="card-body">
    <h5 class="card-title text-danger">
    <i class="ti ti-alert-triangle"></i> THIS IS FOR COMING SOON! 
      <i class="ti ti-alert-triangle"></i> Announcement: System Maintenance Scheduled
    </h5>
    <p class="card-text">
      Please note that our platform will undergo <strong>scheduled maintenance</strong> on:
    </p>
    <ul>
      <li><strong>Date:</strong> Saturday, November 25, 2024</li>
      <li><strong>Time:</strong> 12:00 AM to 6:00 AM (UTC)</li>
    </ul>
    <p>
      During this period, some services may be unavailable. We recommend saving your work and logging out before the maintenance begins.
    </p>
    <p>
      Thank you for your understanding and patience! If you have any questions, feel free to contact 
      <a href="mailto:support@example.com">support@example.com</a>.
    </p>
    <p class="text-info">
      <i class="ti ti-info-circle"></i> Stay tuned for further updates in the notifications panel.
    </p>
  </div>
</div>

  
    </div>
  </div>
</div>


    <div class="col-xl-8 mb-4 col-lg-7 col-12">
      <div class="card h-100">
        <div class="card-header">
          <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title mb-0">{{ __('Requests Statistics') }}</h5>
       
          </div>
        </div>
        @can('read sms')
        <div class="card-body">
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-primary me-3 p-2"><i class="ti ti-file ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $coeCount}}</h5>
                  <small>{{ __('COE') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-primary me-3 p-2"><i class="ti ti-coin ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{ $payslipCount }}</h5>
                  <small>{{ __('Payslip') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-success me-3 p-2"><i class="ti ti-plane ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{ $leaveCount }}</h5>
                  <small>{{ __('Leaves') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-9">
              <div class="d-flex align-items-center">
                <div wire:click='sendPendingMessages' class="badge rounded-pill bg-label-danger me-3 p-2"
                  style="cursor: pointer"><i class="ti ti-devices ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{ $serviceRecords }}</h5>
                  <small>Service Records</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endcan
        @can('create leaves')
        
        <div class="card-body pt-0">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title mb-0">{{ __('Employee Statistics') }}</h5>
       
          </div>
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$activeCount}}</h5>
                  <small>{{ __('Active Employees') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$inActiveCount}}</h5>
                  <small>{{ __('Inactive Employee') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$regularCount}}</h5>
                  <small>{{ __('Regular Employee') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$partTimeCount}}</h5>
                  <small>{{ __('Part time') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$jobOrderCount}}</h5>
                  <small>{{ __('Job Order') }}</small>
                </div>
              </div>
            </div>  
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$casualCount}}</h5>
                  <small>{{ __('Casual') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$consultantCount}}</h5>
                  <small>{{ __('Consultant') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                <h5 class="mb-0">{{$contractServicCount}}</h5>
                  <small>Contract of Service</small>
                </div>
              </div>
            </div>
 
            <div class="btn-group dropend">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false"><i class="ti ti-menu-2 ti-xs me-1"></i>Add New Employee</button>
                <ul class="dropdown-menu">
                  @can('create employees')
                  <li><a class="dropdown-item" href="{{ route('structure-employees') }}"><i
                        class="ti ti-menu-2 ti-xs me-1"></i> {{ __('Employee') }}</a></li>
                  @endcan
                  @can('create employees')
                  <li><a class="dropdown-item" href="{{ route('structure-employees') }}"><i
                        class="ti ti-menu-2 ti-xs me-1"></i> {{ __('Admins') }}</a></li>
                  @endcan
           
                </ul>
              </div>

        </div>
        @endcan
      </div>
    </div>



  <div class="row mt-3">
    <div class="col">
      <div class="card">
        <h5 class="card-header">All Requests</h5>
        
        <div class="table-responsive text-nowrap">

    

<table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-1">{{ __('ID') }}</th>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Request Type') }}</th>
                                        <th style="text-align: center">{{ __('Request Date') }}</th>
                                        <th style="text-align: center">{{ __('Status') }}</th>
                               
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($requests as $request)
                                        <tr>
                                            <td><strong>{{ $request->id }}</strong></td>
                                            <td class="td">    {{ $this->getEmployeeName($request->employee_id) }}</td>
                                            <td class="td">{{ $request->type }}</td>
                                            <td style="text-align: center">{{ $request->created_at->format('m/d/Y') }}</td>
                                            <td style="text-align: center">{{ $request->status }}</td>
                                             
                                            </td>
 



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
                                @forelse($myRequests as $request)
                                    <tr>
                                        <td><strong>{{ $request['id'] }}</strong></td>
                                        <td class="td">{{ $request['type'] }}</td>
            

                                        <td style="text-align: center">{{ \Carbon\Carbon::parse($request['created_at'])->format('m/d/Y') }}</td>
                                        <td style="text-align: center">{{ $request['status'] }}</td>
                                        <td style="text-align: center">
                                            <!-- Actions -->
                                            <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect" data-bs-toggle="modal" data-bs-target="#viewRequestModal{{ $request['id'] }}">
    <span class="ti ti-eye"></span>
</button>

<button type="button" wire:click="confirmDestroyRequest({{ $request['id'] }})" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $request['id'] }}">
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
                                    <i class="bi bi-paperclip me-2"></i>Your Attachments
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

                    <div class="card border-0">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">
                                    Notes from Approver
                                </h6>
                                <p class="mb-0">{{ $request->notes }}</p>
                            </div>
                        </div>

                    <div class="card border-0">
                        <div class="card-body">
                            <h6 class="card-title text-muted mb-3">
                                <i class="bi bi-paperclip me-2"></i>Approver's Attachments
                            </h6>

                            {{-- Single Attachment (requestor_attachment column) --}}
                            @if ($request->approver_attachment)
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
                                            <span>{{ basename($request->approver_attachment) }}</span>
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
                            @if (!$request->approver_attachment && (!$request->attachments || $request->attachments->count() === 0))
                                <div class="alert alert-light text-muted text-center" role="alert">
                                    <i class="bi bi-exclamation-circle me-2"></i>No attachments found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                        <span class="fw-bold text-muted me-2"><i class="bi bi-person me-1"></i>Request Type:</span>
                                        {{ $request->type }}
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
                <button type="button" class="btn btn-danger" wire:click.prevent="destroyRequest" data-bs-dismiss="modal">
                    <i class="bi bi-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endforeach



  
    @endif
  </div>


  {{-- Modals --}}

  
 

  @push('custom-scripts')
  <script>
    function updateClock() {
            const now = new Date();
            const dateOptions = {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            };
            const timeOptions = {
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
            };

            const formattedDate = now.toLocaleDateString('en-US', dateOptions);
            const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

            document.getElementById('date').innerHTML = formattedDate;
            document.getElementById('time').innerHTML = formattedTime;
        }

        setInterval(updateClock, 1000); // Update every second
        updateClock(); // Initial call to display clock immediately
  </script>

<style>
  .calendar {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
  }
  
  .calendar .row {
    margin: 0;
  }

  .calendar .col {
    padding: 0.5rem;
    border: 1px solid transparent;
    transition: background-color 0.3s, border-color 0.3s;
  }

  .calendar .col:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    cursor: pointer;
  }

  #monthYear {
    align-self: center;
  }

  .btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
  }

  .btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
  }
</style>

<style>
.modal-content {
    border-radius: 12px;
}
.modal-header {
    background: linear-gradient(135deg, #3b8132 0%, #FFFFFF 100%);
}
</style>

<script>
  const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  let currentDate = new Date();

  function renderCalendar() {
    const monthYear = document.getElementById("monthYear");
    const daysContainer = document.getElementById("days");

    monthYear.innerText = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    daysContainer.innerHTML = "";

    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
    const lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      daysContainer.innerHTML += `<div class="col"></div>`;
    }

    for (let i = 1; i <= lastDate; i++) {
      daysContainer.innerHTML += `<div class="col">${i}</div>`;
    }
  }

  document.getElementById("prev").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
  });

  document.getElementById("next").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
  });

  document.addEventListener('livewire:load', function () {
    Livewire.on('refreshPage', () => {
        window.location.reload(); // This reloads the page
    });
});

  renderCalendar();
</script>
  @endpush
</div>
