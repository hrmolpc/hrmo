<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'requests'; // Table name, adjust if necessary


    protected $fillable = [
        'employee_id', 'type', 'description', 'status', 'date_from', 'date_to', 
        'is_active', 'created_by', 'updated_by', 'deleted_by', 
        'requestor_attachment', 'approver_attachment'
    ];
}
