<div>

@php
  $configData = Helper::appClasses();
@endphp

@section('title', 'Personal - Messages')

@section('vendor-style')

@endsection

 

 

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Human Resource') }}</li>
    <li class="breadcrumb-item active">{{ __('Messages') }}</li>
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
    
          </table>
        </div>
      </div>
    </div>
  </div>
    </div>
</div>

 

 
</div>
