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
                    Employee's Information History
                </h5>

                @if($history->isNotEmpty())
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Change Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $entry)
                        <tr>
                            <td>{{ ucfirst($entry->field) }}</td>
                            <td>{{ $entry->old_value }}</td>
                            <td>{{ $entry->new_value }}</td>
                            <td>{{ \Carbon\Carbon::parse($entry->created_at)->format('F j, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info text-center">
                No history records found for this employee.
            </div>
        @endif
              </div>
        </div>
    </div>

    {{-- Timeline can be added here if needed --}}

</div>
