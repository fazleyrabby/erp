<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_details', function (Blueprint $table) {
            
            $table->bigincrements('id');

            $table->biginteger('employee_id')->nullable();
            $table->biginteger('sheet_id')->nullable();
            $table->biginteger('bonus_id')->nullable();

            $table->string('month_year')->nullable();           
            $table->string('percentage')->nullable();

            $table->decimal('bonus_amount',10,2)->nullable();

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
        Schema::dropIfExists('bonus_details');
    }
}
