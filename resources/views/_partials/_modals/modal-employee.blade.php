@push('custom-css')   
<style>     
    input::-webkit-outer-spin-button,       
    input::-webkit-inner-spin-button {           
        -webkit-appearance: none;           
        margin: 0;       
    }      
    input[type="number"] {         
        -moz-appearance: textfield;     
    }   
    .form-section { 
        border: 1px solid #e0e0e0; 
        border-radius: 8px; 
        padding: 20px; 
        margin-bottom: 20px; 
        background-color: #f9f9f9; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .section-title { 
        font-weight: bold; 
        margin-bottom: 15px; 
        font-size: 1.25rem;
        color: #333;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style> 
@endpush  

<div wire:ignore.self class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">   
    <div class="modal-dialog modal-xl modal-simple">     
        <div class="modal-content p-0 p-md-5">       
            <div class="modal-body">         
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>         
                <div class="text-center mb-4">           
                    <h3 class="mb-2">{{ $isEdit ? __('Edit Account') : __('New Account') }}</h3>           
                    <p class="text-muted">{{ __('Please fill out the following information') }}</p>         
                </div>         
                <form wire:submit.prevent="submitEmployee" class="row g-3">           
                    
                    <div class="col-12 mb-4 form-section">
                        <div class="section-title">{{ __('Basic Information') }}</div>
                        <div class="row">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('First Name') }}</label>             
                                <input class="form-control" type="text" wire:model="employeeInfo.first_name" required/>           
                            </div>           
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Last Name') }}</label>             
                                <input class="form-control" type="text" wire:model="employeeInfo.last_name" required/>           
                            </div>       
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Gender') }}</label>             
                                <select class="form-select" wire:model="employeeInfo.gender" required>               
                                    <option value="" disabled selected>{{ __('Select Gender') }}</option>               
                                    <option value="male">{{ __('Male') }}</option>               
                                    <option value="female">{{ __('Female') }}</option>             
                                    <option value="other">{{ __('Other') }}</option>             
                                </select>           
                            </div>    
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Email Address') }}</label>             
                                <input type="email" class="form-control" wire:model="employeeInfo.email" placeholder="example@mail.com" required/>           
                            </div>           
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Contact Number') }}</label>             
                                <input type="tel" class="form-control" wire:model="employeeInfo.mobile_number" placeholder="Phone Number" required/>           
                            </div>     
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Birthday') }}</label>             
                                <input type="date" class="form-control" wire:model="employeeInfo.birthday" required/>           
                            </div>         
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Nationality') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.nationality" required/>           
                            </div>           
                   
                            <div class="col-md-8">             
                                <label class="form-label">{{ __('Address') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.address" placeholder="Enter full address" required/>           
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-4 form-section">
                        <div class="section-title">{{ __('Employment Information') }}</div>
                        <div class="row">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Employee ID') }}</label>             
                                <input class="form-control" type="text" value="{{ $isEdit && isset($employeeInfo['employee_id']) ? $employeeInfo['employee_id'] : $this->generateUniqueEmployeeId() }}" readonly/>
                                </div>

                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Employment Status') }}</label>             
                                <select class="form-select" wire:model="employeeInfo.employment_status" required>               
                                    <option value="" disabled selected>{{ __('Select Status') }}</option>               
                                    <option value="regular">{{ __('Regular') }}</option>               
                                    <option value="part_time">{{ __('Part Time') }}</option>               
                                    <option value="job_order">{{ __('Job Order') }}</option>               
                                    <option value="volunteer">{{ __('Volunteer') }}</option>               
                                    <option value="consultant">{{ __('Consultant') }}</option>               
                                    <option value="contract_service">{{ __('Contract Service') }}</option>             
                                </select>           
                            </div>
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Start Date') }}</label>             
                                <input type="date" class="form-control" wire:model="employeeInfo.start_date" required/>           
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Position') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.position" required/>           
                            </div>           
                   
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Department') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.department" placeholder="Enter department" required/>           
                            </div>
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Account Type') }}</label>             
                                <select class="form-select" wire:model="employeeInfo.account_type" required>               
                                    <option value="" disabled selected>{{ __('Select Type') }}</option>               
                                    <option value="employee">{{ __('Employee') }}</option>               
                                    <option value="admin">{{ __('Admin') }}</option>               
                                             
                                </select>           
                            </div>
                        </div>
                        
                    </div>

                    <div class="col-12 mb-4 form-section">
                        <div class="section-title">{{ __('Emergency Contact Information') }}</div>
                        <div class="row">
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Emergency Contact Name') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.emergency_contact_name" placeholder="Emergency Contact Name" required/>           
                            </div>           
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Emergency Contact Number') }}</label>             
                                <input type="tel" class="form-control" wire:model="employeeInfo.emergency_contact_number" placeholder="Phone Number" required/>           
                            </div>           
                            <div class="col-md-4">             
                                <label class="form-label">{{ __('Relation to Employee') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.relation_to_employee" placeholder="Relation" required/>           
                            </div>
                            <div class="col-md-8 mt-3">             
                                <label class="form-label">{{ __('Emergency Contact Address') }}</label>             
                                <input type="text" class="form-control" wire:model="employeeInfo.emergency_contact_address" placeholder="Enter full address" required/>           
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">             
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('Submit') }}</button>             
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">{{ __('Cancel') }}</button>           
                    </div>         
                </form>       
            </div>     
        </div>   
    </div> 
</div>  

@push('custom-scripts')  
@endpush  
