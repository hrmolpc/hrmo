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
      <div class="calendar flex-grow-1">
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
                  <h5 class="mb-0">{{ $accountBalance['is_active'] }}</h5>
                  <small>{{ __('COE') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-primary me-3 p-2"><i class="ti ti-coin ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $accountBalance['balance'] }}</h5>
                  <small>{{ __('Payslip') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-success me-3 p-2"><i class="ti ti-plane ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $messagesStatus['sent'] }}</h5>
                  <small>{{ __('Leaves') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-9">
              <div class="d-flex align-items-center">
                <div wire:click='sendPendingMessages' class="badge rounded-pill bg-label-danger me-3 p-2"
                  style="cursor: pointer"><i class="ti ti-devices ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $messagesStatus['unsent'] }}</h5>
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
                  <h5 class="mb-0">1</h5>
                  <small>{{ __('Active Employees') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Inactive Employee') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Regular Employee') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Part time') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Job Order') }}</small>
                </div>
              </div>
            </div>  
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Volunteer') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
                  <small>{{ __('Consultant') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-secondary me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ count($leaveRecords) }}</h5>
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
        <h5 class="card-header">{{ __('Today Requests')}}</h5>
        
        <div class="table-responsive text-nowrap">

        <div class="col-md-5 mx-4 mb-3">
  <label class="form-label w-100">Search by Request Type</label>
  <input wire:model='employeeInfo.firstName' class="form-control @error('employeeInfo.firstName') is-invalid @enderror" type="text" />
</div>

          <table class="table table-hover">
            <thead>
              <tr>
                <th class="col-1">{{ __('ID') }}</th>
                <th>{{ __('Employee') }}</th>
                <th class="col-1">{{ __('Type') }}</th>
                <th style="text-align: center">{{ __('Details') }}</th>
                <th style="text-align: center">{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($leaveRecords as $leave)
              <tr>
                <td><strong>{{ $leave->id }}</strong></td>
                <td class="td">{{ $this->getEmployeeName($leave->employee_id) }}</td>
                <td>{{ $this->getLeaveType($leave->leave_id) }}</td>
                <td style="text-align: center">
                  <span class="badge bg-label-primary mb-2 me-1" style="font-size: 14px">{{ $leave->from_date . ' --> '
                    . $leave->to_date }}</span>
                  <br>
                  @if ($leave->start_at !== null)
                  <span class="badge bg-label-secondary me-1">{{ Carbon::parse($leave->start_at)->format('H:i') . ' -->
                    ' . Carbon::parse($leave->end_at)->format('H:i') }}</span>
                  @endif
                </td>
                <td style="text-align: center">
                  <button type="button"
                    class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect">
                    <span wire:click.prevent="showEditLeaveModal({{ $leave->id }})" data-bs-toggle="modal"
                      data-bs-target="#leaveModal" class="ti ti-pencil"></span>
                  </button>
                  <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect">
                    <span wire:click.prevent="confirmDestroyLeave({{ $leave->id }})" class="ti ti-trash"></span>
                  </button>
                  @if ($confirmedId === $leave->id)
                  <button wire:click.prevent="destroyLeave" type="button"
                    class="btn btn-xs btn-danger waves-effect waves-light">
                    {{ __('Sure?') }}
                  </button>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">
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

  {{-- Modals --}}
  @include('_partials/_modals/modal-leaveWithEmployee')

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

<<style>
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

  renderCalendar();
</script>
  @endpush
</div>
