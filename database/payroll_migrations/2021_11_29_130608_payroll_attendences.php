<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PayrollAttendences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_attendences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_id')->nullable();
            $table->string('date')->nullable();
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('working_hour')->nullable();
            $table->biginteger('machine_id')->nullable();
	        $table->longtext('note')->nullable();
            $table->enum('time_in_status',['Late','Early','Proper'])->nullable();
            $table->enum('time_out_status',['Late','Early','Proper'])->nullable();
            $table->string('time_in_differnce')->nullable();
            $table->string('time_out_differnce')->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->enum('deleted',['Yes', 'No'])->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_date')->format('d/m/Y')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_attendences');

    }
}
