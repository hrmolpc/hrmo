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

    protected $fillable = [
        'employee_id', 
        'first_name', 'last_name', 'gender', 'email', 'mobile_number',
        'birthday', 'nationality', 'address', 'emergency_contact_name',
        'emergency_contact_number', 'relation_to_employee', 'emergency_contact_address',
        'employment_status', 'start_date', 'position', 'department',
    ];

    // 👉 Links
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

  

    // 👉 Attributes




    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->father_name.' '.$this->last_name;
    }

    public function getShortNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }


    public function getEmployeePhoto()
    {
  
    }
}
