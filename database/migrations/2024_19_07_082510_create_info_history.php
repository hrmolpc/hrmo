<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoHistory extends Migration
{
   // database/migrations/xxxx_xx_xx_create_employee_information_histories_table.php
public function up()
{
    Schema::create('employee_information_histories', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id');
        $table->string('field'); // Name of the field that changed
        $table->text('old_value')->nullable(); // Old value of the field
        $table->text('new_value'); // New value of the field
        $table->string('changed_by'); // Admin/User who made the change
        $table->timestamps();

    });
}


    public function down()
    {
        Schema::dropIfExists('employee_information_histories');
    }
}
