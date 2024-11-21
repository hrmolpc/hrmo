<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile_number')->nullable();
            $table->date('birthday')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->string('employee_id')->unique()->nullable();
            $table->string('employment_status')->nullable();
            $table->date('start_date')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('relation_to_employee')->nullable();
            $table->string('emergency_contact_address')->nullable();
            $table->boolean('is_active')->default(1)->nullable(); // New field
            $table->string('created_by')->nullable(); // New field
            $table->string('updated_by')->nullable(); // New field
            $table->string('deleted_by')->nullable(); // New field
            $table->string('account_type')->nullable(); // New field
            $table->timestamps();
            $table->softDeletes(); // Enable soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
