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

<div class="row match-height">
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card h-50">
            <div class="card-header pb-0">
                <div class="text-center mt-3 mb-5">
                    <img src="{{ asset('assets/img/logo/lpclogo.png') }}" class="img-fluid" 
                         style="object-fit: contain; max-width: 60%; max-height: 100%;">
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 mb-4 col-lg-2 col-1 ">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">Requests Statistics</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    @foreach ($requestTypeCounter as $type => $count)
                        <div class="col-md-3 col-1">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-secondary me-3 p-2">
                                    <i class="ti ti-file ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $count }}</h5>
                                </div>
                            </div>
                            <small> &nbsp; {{ ucfirst($type) }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-body pt-0">
                <h5>Active Employee Statistics</h5>
                <div class="row gy-3">
                    @foreach ($employmentStatusCount as $status => $count)
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-secondary me-3 p-2">
                                    <i class="ti ti-users ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $count }}</h5>
                                    <small>{{ ucwords(str_replace('_', ' ', $status)) }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-body pt-0">
                <h5>Non-Active Employee Statistics</h5>
                <div class="row gy-3">
                    @foreach ($inactiveEmploymentStatusCount as $status => $count)
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-secondary me-3 p-2">
                                    <i class="ti ti-users ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $count }}</h5>
                                    <small>{{ ucwords(str_replace('_', ' ', $status)) }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="btn-group dropend">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-menu-2 ti-xs me-1"></i>Add New Employee
                </button>
                <ul class="dropdown-menu">
                    @can('create employees')
                        <li><a class="dropdown-item" href="{{ route('structure-employees') }}">
                            <i class="ti ti-menu-2 ti-xs me-1"></i> Employee
                        </a></li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col">
        <div class="card">
            <h5 class="card-header">Today Requests</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-1">ID</th>
                            <th>Employee</th>
                            <th class="col-1">Type</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
    @forelse($requests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->employee ? $request->employee->first_name : 'N/A' }}</td>
  
            <td>{{ $request->type }}</td>
            <td style="text-align: center">
                <!-- Add actions like "View", "Edit", etc. -->
                <a href="#" class="btn btn-primary btn-sm">View</a>
                <a href="#" class="btn btn-info btn-sm">Edit</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No requests available for today</td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>
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

</div>
