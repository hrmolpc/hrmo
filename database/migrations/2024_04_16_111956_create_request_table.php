<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->text('type');
            $table->text('description')->nullable(); // Additional details about the request
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('date_from')->nullable(); // Start date for the request
            $table->date('date_to')->nullable(); // End date for the request
            $table->boolean('is_active')->default(1); // Active status
            $table->string('created_by'); // Creator of the request
            $table->string('updated_by'); // Last updater of the request
            $table->string('deleted_by')->nullable(); // Deleter of the request
            $table->text('requestor_attachment')->nullable(); // Attachment from the requestor
            $table->text('approver_attachment')->nullable(); // Attachment from the approver
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
