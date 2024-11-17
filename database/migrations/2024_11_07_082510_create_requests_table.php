<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees'); // Adjust foreign key table name if necessary
            $table->string('type');
            $table->text('description');
            $table->string('status')->default('Pending');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('is_active')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users'); // Adjust if necessary
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Adjust if necessary
            $table->foreignId('deleted_by')->nullable()->constrained('users'); // Adjust if necessary
            $table->string('requestor_attachment')->nullable();
            $table->string('approver_attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
}

