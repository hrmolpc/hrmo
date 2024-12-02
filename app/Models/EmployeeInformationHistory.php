<?php
// app/Models/EmployeeInformationHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeInformationHistory;

class EmployeeInformationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'field',
        'old_value',
        'new_value',
        'changed_by',
    ];

 

    
}
