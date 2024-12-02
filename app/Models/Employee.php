<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $primaryKey = 'employee_id';  // Use employee_id as primary key
public $incrementing = false;  // Ensure it's not auto-incrementing
protected $keyType = 'string';
   
    
    protected $casts = [
        'employee_id' => 'string',  // Ensure employee_id is treated as a string
    ];
 
    protected $fillable = [
        'employee_id', 
        'first_name', 'last_name', 'gender', 'email', 'mobile_number', 'account_type',
        'birthday', 'nationality', 'address', 'emergency_contact_name',
        'emergency_contact_number', 'relation_to_employee', 'emergency_contact_address',
        'employment_status', 'start_date', 'position', 'department',
    ];

    /**
     * Relationship with Request model
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Accessor for full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($employee) {
            foreach ($employee->getChanges() as $field => $newValue) {
                if ($field === 'updated_at') continue; // Skip timestamps

                EmployeeInformationHistory::create([
                    'employee_id' => $employee->employee_id,
                    'field'       => $field,
                    'old_value'   => $employee->getOriginal($field),
                    'new_value'   => $newValue,
                    'changed_by'  => 'Admin123', // ID of the user making the change
                ]);
            }
        });
    }
}