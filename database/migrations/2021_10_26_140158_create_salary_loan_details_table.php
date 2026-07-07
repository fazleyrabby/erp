<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_loan_details', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('loan_id')->nullable();
            $table->string('month_year')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('adjust_amount')->nullable();
            $table->decimal('installment', 12, 2)->nullable();
            $table->enum('loan_status', ['Pending', 'Reject', 'Paid'])->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_date')->format('d/m/Y')->nullable();

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
        Schema::dropIfExists('salary_loan_details');
    }
}
