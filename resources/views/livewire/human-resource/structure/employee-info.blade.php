<div>

    @section('title', 'Employee Info - Structure')

    @section('page-style')
    <style>
        .timeline-icon {
            cursor: pointer;
            opacity: 0;
        }

        .timeline-row:hover .timeline-icon {
            display: inline-block;
            opacity: 1;
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

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div class="d-flex align-items-md-end align-items-sm-start justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item">
                                        <span class="badge rounded-pill bg-label-primary"><i class="ti ti-id"></i> {{ $employee->employee_id }}</span>
                                    </li>
                                    <li class="list-inline-item"><i class="ti ti-building-community"></i>  {{ $employee->address }} </li>
                                    <li class="list-inline-item"><i class="ti ti-phone"></i> {{ $employee->mobile_number }} </li>
                                    <li class="list-inline-item"><i class="ti ti-mail"></i> {{ $employee->email }} </li>
                                    <li class="list-inline-item"><i class="ti ti-rocket"></i> {{ \Carbon\Carbon::parse($employee->start_date)->format('F j, Y') }}</li>


                                </ul>
                            </div>
                            <button type="button" class="btn {{ $employee->is_active ? 'btn-danger' : 'btn-success' }} waves-effect waves-light" wire:click="toggleActive">
                                <span class="ti ti-user-x me-1"></span> {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    Employee Requests
                </h5>
                <div class="table-responsive text-nowrap">
                    <div class="row mx-4 mb-3">
                        <div class="col-md-4">
                            <label class="form-label w-100">Filter by Request</label>
                            <select wire:model='statusFilter' class="form-select @error('statusFilter') is-invalid @enderror">
                                <option value="">Select Request</option>
                                <option value="Pending">Certificate of Employment</option>
                                <option value="Completed">Payslip</option>
                                <option value="Rejected">Leave</option>
                                <option value="Rejected">Service Records</option>
                            </select>
                        </div>
                    </div>

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
                            <tr>
                                <td><strong>123</strong></td>
                                <td class="td">Certificate of Employment</td>
                                <td style="text-align: center">10/20/2024</td>
                                <td style="text-align: center">Pending</td>
                                <td style="text-align: center">
                                    <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect">
                                        <span class="ti ti-eye"></span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect">
                                        <span class="ti ti-trash"></span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Additional rows can be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline can be added here if needed --}}

</div>
