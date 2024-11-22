<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            // Adding the 'id' column for 'requests' table as default auto-increment
            $table->id(); // Default auto-incrementing primary key for 'requests'

            // Foreign key to 'employees' table using 'employee_id' as the primary key
            $table->string('employee_id'); // Referencing 'employee_id' instead of the default 'id'
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade'); // Foreign key constraint

            // Request details
            $table->string('type');
            $table->text('description');
            $table->string('status')->default('Pending');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('is_active')->default(1);

            // Metadata for user actions (created, updated, deleted by)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');

            // Attachments for the request
            $table->string('requestor_attachment')->nullable();
            $table->string('approver_attachment')->nullable();

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
