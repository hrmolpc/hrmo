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
                                    @forelse($coeRecords as $coe)
                                        <tr>
                                            <td><strong>{{ $coe->id }}</strong></td>
                                            <td class="td">{{ $this->getEmployeeName($coe->employee_id) }}</td>
                                            <td style="text-align: center">{{ $coe->request_date }}</td>
                                            <td style="text-align: center">{{ $coe->status }}</td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect">
                                                    <span class="ti ti-eye"></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success rounded-pill btn-icon waves-effect">
                                                    <span wire:click.prevent="showEditCOEModal({{ $coe->id }})" data-bs-toggle="modal" data-bs-target="#coeModal" class="ti ti-check"></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect">
                                                    <span wire:click.prevent="confirmDestroyCOE({{ $coe->id }})" class="ti ti-trash"></span>
                                                </button>
                                                @if ($confirmedId === $coe->id)
                                                    <button wire:click.prevent="destroyCOE" type="button" class="btn btn-xs btn-danger waves-effect waves-light">
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
        </div>
    </div>

</div>
