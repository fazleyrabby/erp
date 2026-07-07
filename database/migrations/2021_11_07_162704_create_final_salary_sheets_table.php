<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalSalarySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_salary_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('month_year')->nullable();
            $table->bigInteger('employee_id')->nullable();
            $table->string('joining_date')->nullable();

            $table->bigInteger('sheet_id')->nullable();
            $table->bigInteger('saved_sheet_id')->nullable();
            $table->bigInteger('account_no')->nullable();

            $table->decimal('consulate', 12)->nullable();
            $table->decimal('basic', 12)->nullable();
            $table->decimal('house_rent', 12)->nullable();
            $table->decimal('medical_allowence', 12)->nullable();
            $table->decimal('company_contribution', 12)->nullable();
            $table->decimal('laundry', 12)->nullable();
            $table->decimal('phone_bill', 12)->nullable();
            $table->decimal('ta_da', 12)->nullable();
            $table->decimal('provident_fund', 12)->nullable();
            $table->decimal('company_provident_fund', 12)->nullable();
            $table->decimal('adjustment', 12)->nullable();
            $table->decimal('step_amount', 12)->nullable();
            $table->decimal('total', 12)->nullable();
            $table->decimal('due', 12)->nullable();
            $table->decimal('deduct_provident_fund', 12)->nullable();
            $table->decimal('loan_installment', 12)->nullable();
            $table->decimal('net_total', 12)->nullable();

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('last_updated_by')->nullable();
            $table->enum('deleted', ['Yes', 'No'])->nullable();
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
        Schema::dropIfExists('final_salary_sheets');
    }
}
