<div>

    @php
        $configData = Helper::appClasses();
    @endphp

    @section('title', 'Employee')

    

    {{-- Alerts --}}
    @include('_partials/_alerts/alert-general')

    <div class="row">
        <div class="col-13 mb-4">
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
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-secondary waves-effect"  >
                                                    <span class="ti ti-eye"></span>
                                                </button>
                                       
                                                <button type="button" class="btn btn-sm btn-tr rounded-pill btn-icon btn-outline-danger waves-effect">
                                                    <span class="ti ti-trash"></span>
                                                </button>
                                        
                                            
                                            </td>
                                        </tr>
                             
                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="newRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRequestModalLabel">New Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="requestForm">
                    <div class="mb-3">
                        <label for="requestType" class="form-label">Request Type</label>
                        <select class="form-select" id="requestType" required onchange="toggleDateFields()">
                            <option value="">Choose...</option>
                            <option value="Certificate of Employment">Certificate of Employment</option>
                            <option value="Payslip">Payslip</option>
                            <option value="Leave">Leave</option>
                            <option value="Service Records">Service Records</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="requestDetails" class="form-label">Reason for filing</label>
                        <textarea class="form-control" id="requestDetails" rows="3" required></textarea>
                    </div>
                    <div class="mb-3" id="leaveDates" style="display: none;">
                        <label for="dateFrom" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="dateFrom">
                    </div>
                    <div class="mb-3" id="leaveDatesTo" style="display: none;">
                        <label for="dateTo" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="dateTo">
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDateFields() {
        const requestType = document.getElementById('requestType').value;
        const leaveDates = document.getElementById('leaveDates');
        const leaveDatesTo = document.getElementById('leaveDatesTo');

        if (requestType === 'Leave') {
            leaveDates.style.display = 'block';
            leaveDatesTo.style.display = 'block';
        } else {
            leaveDates.style.display = 'none';
            leaveDatesTo.style.display = 'none';
        }
    }
</script>


</div>
