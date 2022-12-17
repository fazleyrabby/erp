<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeScheduleGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_schedule_groups', function (Blueprint $table) {

            $table->increments('id');
            $table->biginteger('group_id')->nullable();
            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();
            $table->string('working_hour')->nullable();

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
        Schema::dropIfExists('tbl_payroll_time_schedule_groups');
    }
}
