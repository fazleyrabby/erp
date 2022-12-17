<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_instructions', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('month_year')->nullable();
            $table->bigInteger('sheet_id')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->bigInteger('mother_account_no')->nullable();
            $table->longText('footer_instruction')->nullable();
            $table->longText('letter_body')->nullable();

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
        Schema::dropIfExists('salary_instructions');
    }
}
