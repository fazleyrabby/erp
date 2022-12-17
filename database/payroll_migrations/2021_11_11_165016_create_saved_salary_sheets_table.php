<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedSalarySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_salary_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('month_year')->nullable();
            $table->bigInteger('sheet_id')->nullable();
            $table->decimal('company_payable_net_salary')->nullable();
          

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
        Schema::dropIfExists('saved_salary_sheets');
    }
}
